<?php add_shortcode('cart', 'CartList');

/*
Shows the cart and then takes the customer through to checkout
*/

function CartList() 
{
    $cart = new Cart();
    $action = isset($_POST['c_action']) ? $_POST['c_action'] : '';
    
    switch ($action) 
    {
    case 'orderdetails':
        //$cart->IsValid();
        $view = new SSC_View('cart.orderdetails');
        $view->Set('cart', $cart);
        $view->Set('action', 'confirm-and-place-order');
        echo $view->Render();
    break;
    case 'confirm-and-place-order':
        
        if (!$cart->IsValid()) 
        {
            $view = new SSC_View('cart.orderdetails');
            $view->Set('action', 'confirm-and-place-order');
            $view->Set('cart', $cart);
            echo $view->Render();
        }
        else
        {
            $view = new SSC_View('cart.confirmandplace');
            $view->Set('cart', $cart);
            $view->Set('tc', $tc);
            $view->Set('companyName', PluginSettings::CompanyName());
            $view->Set('action', 'sendorder');
            echo $view->Render();
        }
    break;
    case 'sendorder':
        
        if (!$cart->IsValid()) 
        {
            $view = new SSC_View('cart.orderdetails');
            $view->Set('action', 'confirm-and-place-order');
            $view->Set('cart', $cart);
            echo $view->Render();
        }
        else
        {
            
            if ($cart->SendOrder()) 
            {
                $view = new SSC_View('cart.sent');
                $view->Set('companyName', PluginSettings::CompanyName());
                echo $view->Render();
            }
        }
    break;
    default:
        
        if ($cart->TotalLines() == 0) 
        {
            $view = new SSC_View('cart.empty');
            echo $view->Render();
        }
        else
        {
            $view = new SSC_View('cart.list');
            $view->Set('totalLines', $cart->TotalLines());
            $view->Set('cart', $cart);
            $view->Set('action', 'orderdetails');
            echo $view->Render();
        }
    break;
    }
}