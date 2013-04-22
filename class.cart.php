<?php

class Cart
{
    private $Title;
    private $FirstName;
    private $LastName;
    private $Address;
    private $Email;
    private $Tel;
    //private $Sent;
    private $ErrorMessage;
    private $CartLines;
    public 
    function __get($name) 
    {
        
        return $this->$name;
    }
    public 
    function __set($name, $value) 
    {
        $this->$name = $value;
        $this->SaveState();
    }
    private 
    function SaveState() 
    {
            
        //set_transient( PluginSettings::PluginName(), $cart, PluginSettings::Expiration() );
        $test=PluginSettings::CompanyName();
        $_SESSION[PluginSettings::CompanyName() ] = $this;
    }
    private 
    function GetState() 
    {

        //return get_transient( PluginSettings::PluginName() );
        
        if (isset($_SESSION[PluginSettings::CompanyName() ])) 
        {
            
            return $_SESSION[PluginSettings::CompanyName() ];
        }
    }
    
    function IsValid() 
    {

        //Title
        
        if (strlen($this->Title) == 0) 
        {
            $this->ErrorMessage = "Please enter your title.";
        }

        //FirstName
        
        if (strlen($this->FirstName) == 0) 
        {
            $this->ErrorMessage = "Please enter your first name or initial.";
        }

        //LastName
        
        if (strlen($this->LastName) == 0) 
        {
            $this->ErrorMessage = "Please enter your last name.";
        }

        //Address
        
        if (strlen($this->Address) == 0) 
        {
            $this->ErrorMessage = "Please enter your address.";
        }

        //Email
        
        if (strlen($this->Email) == 0) 
        {
            $this->ErrorMessage = "Please enter your email address.";
        }

        //Tel
        
        if (strlen($this->Tel) == 0) 
        {
            $this->ErrorMessage = "Please enter your telephone number.";
        }

        //email invalid address
        
        if (!filter_var($this->Email, FILTER_VALIDATE_EMAIL)) 
        {
            $this->ErrorMessage = "Please enter a valid email address.";
        }
        $valid = true;
        
        if (strlen($this->ErrorMessage) > 0) $valid = false;
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') $this->ErrorMessage = "";
        
        return $valid;
    }
    
    function __construct() 
    {
        $this->CartLines = array();

        //retrieve the cart from the database if it is there
        $cart = $this->GetState();
        
        if ($cart != false) 
        {
            $this->Title = $cart->Title;
            $this->FirstName = $cart->FirstName;
            $this->LastName = $cart->LastName;
            $this->Address = $cart->Address;
            $this->Email = $cart->Email;
            $this->Tel = $cart->Tel;
            $this->CartLines = $cart->CartLines;
        }

        //check for any unprocessed requests
        $this->CheckRequest();
    }
    
    function CheckRequest() 
    {

        // check for unprocessed requests and do whatever action is necessary
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            
            if (isset($_POST['Title'])) 
            {
                $this->Title = filter_var($_POST['Title'], FILTER_SANITIZE_STRING);
                unset($_POST['Title']);
            }
            
            if (isset($_POST['FirstName'])) 
            {
                $this->FirstName = filter_var($_POST['FirstName'], FILTER_SANITIZE_STRING);
                unset($_POST['FirstName']);
            }
            
            if (isset($_POST['LastName'])) 
            {
                $this->LastName = filter_var($_POST['LastName'], FILTER_SANITIZE_STRING);
                unset($_POST['LastName']);
            }
            
            if (isset($_POST['Address'])) 
            {
                $this->Address = filter_var($_POST['Address'], FILTER_SANITIZE_STRING);
                unset($_POST['Address']);
            }
            
            if (isset($_POST['Email'])) 
            {
                $this->Email = filter_var($_POST['Email'], FILTER_SANITIZE_STRING);
                unset($_POST['Email']);
            }
            
            if (isset($_POST['Tel'])) 
            {
                $this->Tel = filter_var($_POST['Tel'], FILTER_SANITIZE_NUMBER_INT);
                unset($_POST['Tel']);
            }
            $this->SaveState();
            
            $action = isset($_POST['cart_action']) ? $_POST['cart_action'] : '';
            
            switch ($action) 
            {
            case 'add':
                $cartLine = new CartLine();
                $cartLine->ProductID = $_POST['ProductID'];
                
                //get the prices
                $product = Products::GetProduct($cartLine->ProductID);
                $prices = $product->Prices;
                
                //get the quantity, type and price
                $cartLine->Quantity = $prices[$_POST['PriceID']]->Quantity;
                $cartLine->Type = $prices[$_POST['PriceID']]->Type;
                $cartLine->Price = $prices[$_POST['PriceID']]->Price;
                $cartLine->PriceID = $_POST['PriceID'];

                $this->Add($cartLine);
                
                break;
            case 'remove':
                $productID = $_POST['ProductID'];
                $priceID = $_POST['PriceID'];
                $this->Remove($productID,$priceID);
                
                break;
            case 'changeqty':
                $id = $_POST['ID'];
                $product = Products::GetProduct($id);
                $quantity = number_format(filter_var($_POST['Quantity'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

                //find the price
                
                foreach ($product->Prices as $price) 
                {
                    
                    if ($price->Quantity == $quantity) $price = $price->Price;
                }
                $this->ChangeQuantity($id, $quantity, $price);
                
                break;
                
            case 'emptycart':
                $this->EmptyCart();
                
                break;
                
            }
            unset($_POST['cart_action']);
        }
    }
    
    function SendOrder() 
    {
        
        if (!$this->IsValid()) 
            return false;

        //prepare the content
        $postTitle = $this->Title . '  ' . $this->FirstName . ' ' . $this->LastName;
        $postContent = "<h3>Customer Details:</h3>";
        $postContent.= $this->CustomerHtml();
        $postContent.= "<h3>Order Details:</h3>";
        $postContent.= $this->CartLinesHtml(false, false);

        //create a new post of type dm_order
        global $user_ID;
        $new_post = array(
            'post_title' => $postTitle,
            'post_content' => $postContent,
            'post_excerpt' => $this->CustomerHtml() ,
            'post_status' => 'private',
            'post_date' => date('Y-m-d H:i:s') ,
            'post_author' => $user_ID,
            'post_type' => PluginSettings::OrderPostType() ,
            'post_category' => array(
                0
            )
        );

        //insert the post
        $post_id = wp_insert_post($new_post);

        //save the cart to the post meta
        add_post_meta($post_id, PluginSettings::OrderPostType() , $this, false);

        //notify admin
        $headers = "From:" . PluginSettings::CompanyName() . " <" . get_bloginfo('admin_email') . ">\r\n";
        $headers.= "Reply-To: " . get_bloginfo('admin_email') . "\r\n";
        $headers.= "MIME-Version: 1.0\r\n";
        $headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = "<p>You have a new online order:</p>";
        $message.= "<p><a href=\"" . get_permalink($post_id) . " \">" . get_permalink($post_id) . "</a></p>";
        wp_mail(get_bloginfo('admin_email') , $postTitle, $message, $headers);

        //notify the customer
        $headers = "From:" . PluginSettings::CompanyName() . " <" . get_bloginfo('admin_email') . ">\r\n";
        $headers.= "Reply-To: " . get_bloginfo('admin_email') . "\r\n";
        $headers.= "MIME-Version: 1.0\r\n";
        $headers.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = "<p>Thank you for your order:</p>";
        $message.= "<p>Order Summary:</p>";
        $message.= $postContent;
        wp_mail($this->Email, "Your Order With " . PluginSettings::CompanyName() , $message, $headers);

        $this->SaveState();
        return true;
    }

    /*
    outputs the customer details
    */
    
    function CustomerHtml() 
    {
        $view = new SSC_View('cart.customer');
        $view->Set('cart', $this);
        return $view->Render();
    }

    /*
    outputs the table of product lines
    */
    
    function CartLinesHtml($withLink = false, $withRemove = false, $withChangeQuantity = false) 
    {
        $view = new SSC_View('cart.lines');
        $view->Set('cart', $this);
        $view->Set('withChangeQuantity', $withChangeQuantity);
        $view->Set('withRemove',$withRemove);
        $view->Set('currencySymbol',PluginSettings::CurrencySymbol());
        return $view->Render();
    }
    
    function TotalPrice() 
    {
        $totalPrice = 0;
        
        foreach ($this->CartLines as $cartLine) 
        {
            $totalPrice+= $cartLine->LinePrice();
        }
        
        return number_format($totalPrice, 2);
    }
    
    function EmptyCart() 
    {
        $this->CartLines = array();
        $this->SaveState();
    }
    
    function TotalLines() 
    {
        
        return count($this->CartLines);
    }
    
    function Add($cartLine) 
    {

        //check that the product id exists
        $product = Products::GetProduct($cartLine->ProductID);
        
        if ($product == null) 
            return;
        
        if ($cartLine->Quantity == 0) 
            return;
        
        foreach ($this->CartLines as $key => $line) {
            if ( $line->ProductID == $cartLine->ProductID and $line->PriceID == $cartLine->PriceID) {
                $thisKey = $key;
            }
        }
        
        if ( ! isset($thisKey) )
        {
            $this->CartLines[] = $cartLine;
        }
        else
        {
            $this->CartLines[$thisKey]->Quantity+= $cartLine->Quantity;
            $this->CartLines[$thisKey]->Price+= $cartLine->Price;
        }
        $this->SaveState();
    }
    
    function Remove($productID,$priceID)
    {
        //search for line
        foreach ($this->CartLines as $key => $line) {
            if ( $line->ProductID == $productID && $line->PriceID == $priceID)
                $thisKey = $key;            
            
        }
        
        //remove if it exists
        if ( isset($thisKey) )
            unset($this->CartLines[$thisKey]);
        
        $this->SaveState();
    }
    
    function ChangeQuantity($id, $quantity, $price) 
    {
        
        if ($this->CartLines[$id] != null) 
        {
            $this->CartLines[$id]->Quantity = $quantity;
            $this->CartLines[$id]->Price = $price;
        }
        $this->SaveState();
    }
    
    function CartMenuHtml() 
    {
        $view = new SSC_View('cart.menu');
        $view->Set('totalLines', $this->TotalLines());
        return $view->Render();
    }
}

class CartLine
{
    var $ProductID;
    var $PriceID;
    var $Quantity;
    var $Price;
    var $Type;
    
    function LinePrice() 
    {
        return number_format($this->Price, 2);
    }
    public 
    function RemoveHtml() 
    {
        $view = new SSC_View('cartline.remove');
        $view->Set('cartLine', $this);
        return $view->Render();
    }
}

