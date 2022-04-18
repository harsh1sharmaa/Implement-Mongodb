<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;



class OneController extends Controller
{

    public function getDataAction()
    {

        // phpinfo();

        $m = $this->mongo;

        // echo "Connection to database successfully";
        // select a database
        $db = $m->store;

        // echo "Database mydb selected";

        // $collection = $db->createCollection("mycol");
        // echo "Collection created succsessfully";

        $collection = $db->products;
        // echo "Collection selected succsessfully";



        $ans = $collection->find();

        return $ans;
        // echo "<pre>";
        // print_r($ans);

        // foreach ($ans as $key => $val) {
        //     echo $val->name;
        //     echo "<br>";
        // }











        // $collection = $conn->text->student;

        // $ans = $collection->find(array('name' => "manoj"));
        // echo "<pre>";
        // print_r($ans);

        // foreach ($ans as $key => $value) {

        //     echo  $key;
        //     echo "<br>";
        //     echo $value;
        // }


        // echo "register";
        // die;
    }

    public function insertAction()
    {

        $data = $this->request->getPost();

        // echo "<pre>";
        // print_r($data);
        // die;
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

                    // array_push($objOfVariation, $key=>$val);

                    $objOfVariation = $objOfVariation + [$key => $val];
                }

                // $vari = array($data["variname" . $i] => $data["varivalue" . $i], "price" => $data["variprice" . $i]);

                array_push($variation, $objOfVariation);
                // $additional = $additional + [$data["variname" . $i] => $data["atvalue" . $i]];
            }
            $doc = array("info" => $detail, "additional" => $additional, "variation" => $variation);
            // echo "<pre>";
            // print_r($doc);
            // die;



            // $doc = array(
            //     "name" => $data['name'],
            //     "age" => $data['age'],
            // );
            $m = $this->mongo;
            $db = $m->store;
            $collection = $db->products;
            $success = $collection->insertOne($doc);
        } else {
            $data = $this->getDataAction();

            // echo "<pre>";
            // print_r($data);
            // die;
            $this->view->message = $data;
        }
    }



    public function deleteAction()
    {

        $data = $this->request->get();


        // echo " Userdash";
        // print_r($data);
        // die;

        if (isset($data['submit'])) {


            // echo " Userdash";
            // die;
            $id = $data['id'];
            // echo $id;
            // die;

            $m = $this->mongo;

            $db = $m->store;
            $collection = $db->products;

            $success = $collection->deleteOne(array("_id" => new MongoDB\BSON\ObjectId("$id")));
            // $success = $collection->deleteOne("_id" $id);

            // print_r($success);
            // echo " Userdash";
            // die;
        }
        $this->response->redirect("one/insert");
    }

    public function editAction()
    {
        $id = $this->request->getpost('id');
        // echo $id;
        // echo "ret";
        // die;

        $m = $this->mongo;

        $db = $m->store;
        $collection = $db->products;

        $success = $collection->find(array("_id" => new MongoDB\BSON\ObjectId("$id")));
        // echo "<pre>";
        // print_r($success);
        // die;
        foreach ($success as $key => $value) {
            echo "<pre>";
            print_r($value);
            // $info=$value->info;
            // $additional=$value->additional;
            // $variation=$value->variation;


        }
        die;
        //  echo "<pre>";
        // print_r($info);
        // $infostr="";
        //     foreach($info as $key => $value){
        //         $info.='<div>category <input type="text" name="category" required="required"></div>';
        //         echo $key;
        //         echo $value;
        //     }
        $this->view->message = $success;

        // die();
    }

    public function getdatabyidAction()
    {

        // return "hello";
        // $id='625a55478d5ef6d9d00a9e02';


        $id = $this->request->getpost('id');
        $m = $this->mongo;

        // return $id;

        $db = $m->store;
        $collection = $db->products;

        $success = $collection->findOne(array("_id" => new MongoDB\BSON\ObjectId("$id")));
        //    echo "<pre>";
        //    print_r(($success));
        echo json_encode($success);
        die;
        // foreach ($success as $key => $value) {
        //     print_r($value);
        //     die();
        // }
        // return $success;
        // die;
    }

    public function orderAction()
    {

        $data = $this->getDataAction();


        $post = $this->request->getPost();


        if (isset($post['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

            //     echo $post['id'];
            //     $id = $post['id'];
            // echo (date("Y-m-d H:i:s"));
            // die;
            echo "<pre>";
            print_r($post);
            // die();
            $productId = $post['id'];
            $variation = $post['variname'];
            $coustomername = $post['coustomername'];
            $quantity = $post['quantity'];
            $createdate = date("Y-m-d");
            // $createdate = new Timestamp();


            $m = $this->mongo;

            $db = $m->store;
            $collection = $db->order;

            $doc = array("productId" => $productId, "variation" => $variation, "coustomername" => $coustomername, "quantity" => $quantity, "created" => $createdate, "status" => "paid");

            $success = $collection->insertOne($doc);
            // $post='';

            //     $product = $collection->find(array("_id" => new MongoDB\BSON\ObjectId("$id")));

            //     $this->view->product = array("product" => $product->toArray(), "id" => $id);
        }

        $this->view->message = $data;
    }

    public function orderlistAction()
    {

        // echo "Order List";
        $data = $this->request->getPost();
        // echo "<pre>";
        // print_r($data);
        // die;
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

            // echo "<pre>";
            // print_r($data);
            // die();
            $status = $data['getstatus'];
            $date = $data['filterdate'];

            $filterdata = $this->filterAction($status, $date);
            $this->view->message = $filterdata;

            // foreach ($filterdata as $key => $value) {
            //     echo "<pre>";
            //     print_r($value);
            // }
            // die();
        } else {

            $ans = $collection->find();
            $this->view->message = $ans;
        }

        // return $id;





        // echo "<pre>";
        // print_r($ans);


    }
    public function filterAction($status, $date)
    {
        // echo $status;
        // echo $date;
        // die();
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
            // foreach ($ans as $key => $value) {
            //     echo "<pre>";
            //     print_r($value);
            // }
            // return $ans;



            // die();
        }

        // echo $selecteddate;
        // die();



        $ans = $collection->find(['$and' => [["status" => "$status"], ['$and' => [["created" => ['$lte' => $todaydate]], ["created" => ['$gte' => $selecteddate]]]]]]);
        // $ans = $collection->find();


        foreach ($ans as $key => $value) {
            echo "<pre>";
            print_r($value);
        }

        die;
        return $ans;
    }
    // }
}
