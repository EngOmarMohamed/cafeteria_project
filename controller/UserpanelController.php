<?php

class UserpanelController{

	function index(){

		if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $product = new ProductModel();

            $product->data = 'all';
            $product->condition = 'no';
            $result = $product->select();
            $num_results = mysqli_num_rows($result);
            $products = array();
            for ($i = 0; $i < $num_results; $i++) {
                $products[] = mysqli_fetch_row($result);
            }



            $room = new RoomModel();
            $room->data = 'all';
            $room->condition = 'no';
            $resultRom = $room->select();
            $num_rom_results = mysqli_num_rows($resultRom);
            $room = array();
            for ($i = 0; $i < $num_rom_results; $i++) {
                $rooms[] = mysqli_fetch_row($resultRom);
            }

            $row = array( $rooms,$products); 
            $template = new Template();
            //$template->render("userpanel/index.php",$products);
            $template->render("userpanel/index.php",$row);

        } 

        else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $userId=$_REQUEST["user_id"];
            $roomId=$_REQUEST["room_id"];
            $orderDate=$_REQUEST["order_date"];
            $notes=$_REQUEST["note"];
            $status=$_REQUEST["status"];
            $totalPrice=$_REQUEST["t_price"];

            

            $order = new OrderModel();
            $order->user_id=$userId;
            $order->room_id=$roomId;
            $order->order_date=$orderDate;
            $order->notes=$notes;
            $order->status=$status;
            $order->total_price= $totalPrice;

            // $order->data=array('user_id' => "'$userId'", 'room_id' => "'$roomId'", 'order_date' => "'$orderDate'",'notes' => "'$notes'", 'status' => "'$status'", 'total_price' => "'$totalPrice'");

             $order->insert();
    
            

///////////////////////////////////////////order details//////////////////////////////////////////////
            $orderid = new OrderModel();

            $orderid->data = array('id');;
            $orderid->condition = array('order_date'=>"'$orderDate'");;
            $orderid_result = $orderid->select();

            
            $order_results = mysqli_num_rows($orderid_result);
            $order_id = array();
            for ($i = 0; $i < $order_results; $i++) {
                $order_id [] = mysqli_fetch_row($orderid_result);
            }

            //print_r($order_id);
            //echo $order_id[0][0];

            $orders= array();

            $orderDetails = json_decode($_REQUEST["order_details"], true); 
            //var_dump($orderDetails); 
            foreach ($orderDetails as $prod_id => $prod_quantity) {
                array_push($orders, "(".$order_id[0][0].",".$prod_id.",".$prod_quantity.") ");
            }

            //print_r($orders);
            $orders = implode(",", $orders);

            $details = new OrderdetailsModel();

            $details->orderdetails = $orders;
            $details->insertMultiple();
            


////////////////////////////////////////////////////////////////////////////////////////////

            $product = new ProductModel();

            $product->data = 'all';
            $product->condition = 'no';
            $result = $product->select();

            
            $num_results = mysqli_num_rows($result);
            $products = array();
            for ($i = 0; $i < $num_results; $i++) {
                $products[] = mysqli_fetch_row($result);
            }



            $room = new RoomModel();
            $room->data = 'all';
            $room->condition = 'no';
            $resultRom = $room->select();
            $num_rom_results = mysqli_num_rows($resultRom);
            $room = array();
            for ($i = 0; $i < $num_rom_results; $i++) {
                $rooms[] = mysqli_fetch_row($resultRom);
            }

            $row = array( $rooms,$products); 

            $template = new Template();
            $template->render("userpanel/index.php",$row);

        }     

	}
}

?>

