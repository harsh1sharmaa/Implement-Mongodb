<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;



class OneController extends Controller
{

    public function getDataAction()
    {

       

        $m = $this->mongo;

      
        $db = $m->store;

       

        $collection = $db->products;
      



        $ans = $collection->find();

        return $ans;
      
    }

    public function insertAction()
    {
        $data = $this->request->getPost();
        if (isset($data['search'])) {
            $productname = $data['productname'];
            $m = $this->mongo;
            $db = $m->store;
            $collection = $db->products;
            $success = $collection->find(array("info.name" => $productname));
            $this->view->message = $success;
        } elseif (isset($data['submit'])) {

            $addiCount = $data['max'];
            $variCount = $data['varimax'];
            $name = $data['name'];
            $category = $data['category'];
            $price = $data['price'];
            $stock = $data['stock'];
            $detail = array("name" => $name, "category" => $category, "price" => $price, "stock" => $stock);
            $additional = array();
            $variation = array();

            for ($i = 0; $i < $addiCount; $i++) {

                $additional = $additional + [$data["atname" . $i] => $data["atvalue" . $i]];
            }

            for ($i = 0; $i < $variCount; $i++) {

                $attributecount = $data['attricount' . $i];
                $objOfVariation = array();
                for ($j = 0; $j <= $attributecount; $j++) {
                    $key = $data['attriname' . $i . '' . $j];
                    $val = $data['attrival' . $i . '' . $j];

                   
                    $objOfVariation = $objOfVariation + [$key => $val];
                }

              

                array_push($variation, $objOfVariation);
               
            }
            $doc = array("info" => $detail, "additional" => $additional, "variation" => $variation);
            
            $m = $this->mongo;
            $db = $m->store;
            $collection = $db->products;
            $success = $collection->insertOne($doc);
        } else {
            $data = $this->getDataAction();

         
            $this->view->message = $data;
        }
    }



    public function deleteAction()
    {

        $data = $this->request->get();



        if (isset($data['submit'])) {


          
            $id = $data['id'];
           

            $m = $this->mongo;

            $db = $m->store;
            $collection = $db->products;

            $success = $collection->deleteOne(array("_id" => new MongoDB\BSON\ObjectId("$id")));
          
        }
        $this->response->redirect("one/insert");
    }

  

    public function getdatabyidAction()
    {

        $id = $this->request->getpost('id');
        $m = $this->mongo;

     
        $db = $m->store;
        $collection = $db->products;

        $success = $collection->findOne(array("_id" => new MongoDB\BSON\ObjectId("$id")));
       
        echo json_encode($success);
        die;
      
    }

    public function orderAction()
    {

        $data = $this->getDataAction();


        $post = $this->request->getPost();


        if (isset($post['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<pre>";
            print_r($post);
            // die();
            $productId = $post['id'];
            $variation = $post['variname'];
            $coustomername = $post['coustomername'];
            $quantity = $post['quantity'];
            $createdate = date("Y-m-d");
       

            $m = $this->mongo;

            $db = $m->store;
            $collection = $db->order;

            $doc = array("productId" => $productId, "variation" => $variation, "coustomername" => $coustomername, "quantity" => $quantity, "created" => $createdate, "status" => "paid");

            $success = $collection->insertOne($doc);
           
        }

        $this->view->message = $data;
    }

    public function orderlistAction()
    {

       
        $data = $this->request->getPost();

        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->order;

        if (isset($data['status'])) {

            $status = $data['status'];
            $orderId = $data['orderId'];

            $collection->updateOne(["_id" => new MongoDB\BSON\ObjectId("$orderId")], ['$set' => ["status" => $status]]);
            $ans = $collection->find();
            $this->view->message = $ans;
        } elseif (isset($data['submit'])) {

            $data = $this->request->getPost();

          
            $status = $data['getstatus'];
            $date = $data['filterdate'];

            $filterdata = $this->filterAction($status, $date);
            $this->view->message = $filterdata;

          
        } else {

            $ans = $collection->find();
            $this->view->message = $ans;
        }



    }
    public function filterAction($status, $date)
    {
       
        $m = $this->mongo;
        $db = $m->store;
        $collection = $db->order;
        $todaydate = date("Y-m-d");
        $selecteddate = $todaydate;
        if ($date == "today") {
            $selecteddate = $todaydate;
        } elseif ($date == "this week") {
            $selecteddate = date('d-m-Y', strtotime($todaydate . ' -7 days'));
        } elseif ($date == "this month") {
            $selecteddate = date('d-m-Y', strtotime($todaydate . ' -30 days'));
        } else {

            $data = $this->request->getpost();
            echo "<pre>";
            print_r($data);
            $stdate = $data['stdate'];
            $endate = $data['endate'];
            $ans = $collection->find(['$and' => [["created" => ['$lte' => $endate]], ["created" => ['$gte' => $stdate]]]]);
         
        }

      



        $ans = $collection->find(['$and' => [["status" => "$status"], ['$and' => [["created" => ['$lte' => $todaydate]], ["created" => ['$gte' => $selecteddate]]]]]]);
      

        foreach ($ans as $key => $value) {
            echo "<pre>";
            print_r($value);
        }

        die;
        return $ans;
    }
    
}
