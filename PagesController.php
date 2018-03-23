<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Profile;

class PagesController extends Controller
{
    public function index() {
        // $title = "Welcome to the Index Page";
        // return view('pages.index')->with('title', $title);
        return view('pages.index');
    }
    public function find() {
        return view('pages.find');
    }


    public function found(Request $request) {
        //request location from form;
        $location = strip_tags ($request->input('location'));
        $number = strip_tags ($request->input('number'));
        $measure = strip_tags ($request->input('measure'));


        function googleAPI($location) {
            /**
             * Build The API:
             * This function relives $location as a parameter will return the lat and lng of any location its given as an array.
             * Uses google maps API
             */

            //construct the array
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );

            //call the API
            $maps_json = file_get_contents("https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . urlencode($location) . "&key=AIzaSyD_9VyLJ01BNKW2oIB6CEXIqU9sHYhKKBs", false, stream_context_create($arrContextOptions));
            $map_array = json_decode($maps_json, true);

            //get status
            $status = $map_array['status'];

            //check if $location is valid using status
            if($status == "OK"){
                //get the lat and lng of the $location
                $lat = $map_array['results'][0]['geometry']['location']['lat'];
                $lng = $map_array['results'][0]['geometry']['location']['lng'];
                $formatted = $map_array['results'][0]['formatted_address'];

                //returns that lat and lng as an array
                return array("lat"=>$lat, "lng"=>$lng, "formatted"=>$formatted);
            }
            else {
                return array("lat"=>"null", "lng"=>"null", "formatted"=>"null", "status"=>"Error");
            }

        }

        function getInfo($APIarray, $string) {
            /**
             * This function receives the API array and a key as string as parameters and will return
             * the corresponding value to the key string.
             */
            foreach($APIarray as $x => $x_value) {
                if ($x == $string) {
                    return $x_value;
                }
            }
        }

        function haversineGreatCircleDistance(
            $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
        {
            /**
             * Calculates the great-circle distance between two points, with
             * the Haversine formula.
             * float $latitudeFrom Latitude of start point in [deg decimal]
             * float $longitudeFrom Longitude of start point in [deg decimal]
             * float $latitudeTo Latitude of target point in [deg decimal]
             * float $longitudeTo Longitude of target point in [deg decimal]
             * float $earthRadius Mean earth radius in [m]
             * return float Distance between points in [m] (same as earthRadius)
             */

            // convert from degrees to radians
            $earthRadius = 6371000;
            $latFrom = deg2rad($latitudeFrom);
            $lonFrom = deg2rad($longitudeFrom);
            $latTo = deg2rad($latitudeTo);
            $lonTo = deg2rad($longitudeTo);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            return $angle * $earthRadius;
        }

        function getDistanceInMiles($i) {
            /**
             * convects maters to miles and rounds to the nearest thousandth.
             */
            return round($i*0.000621371192,1);
        }

        function getDistanceInKM($i) {
            /**
             * convects maters to kilometers and rounds to the nearest thousandth.
             */
            return round($i*0.001,1);
        }

        function getDB() {
            /**
             * gets all profiles from databace and returns it as a JSON object
             */
            $Profile = Profile::select('id', 'branch_id', 'address', 'image', 'name', 'title', 'lat', 'lng')->get();
            return $Profile;
        }

        function  main($location, $number, $measure) {
            /**
             * Main Function
             */

            //Call Google API
            $APIarray = googleAPI($location);

            //Check status for Errors like invalid a location
            $status = getinfo($APIarray, "status");
            if ($status == "Error"){
                //return fully packaged data as a JSON object
                return json_encode(array(array("status"=>$status)), true);
            }

            //get lat and lan for $location from the Google API
            $latitudeFrom = getinfo($APIarray, "lat");
            $longitudeFrom = getinfo($APIarray, "lng");

            //get formatted address
            $formatted = getinfo($APIarray, "formatted");

            //Get Profiles From DB as a JSON object
            $database = getDB();

            //empty data array to be filled with profile Data
            $profileData = array();

            //loop over profile JSON object
            foreach ($database as $value) {
                //all db
                $id = $value['id'];
                $address = $value['address'];
                $image = $value['image'];
                $name = $value['name'];
                $title = $value['title'];
                $latitudeTo = $value['lat'];
                $longitudeTo = $value['lng'];

                //use the Haversine formula to get a $length in meters
                $length = haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo);

                //Check $measurement type
                if ($measure == "ML") {
                    $distance = getDistanceInMiles($length);
                    $type = "Miles";
                } elseif ($measure == "KM") {
                    $distance = getDistanceInKM($length);
                    $type = "kilometers";
                }

                //Check if $distance is within specified in $number field.
                if ($distance <= $number) {

                    //package data from into an array
                    array_push($profileData,array("id"=>$id, "address"=>$address,"image"=>$image,"name"=>$name,"title"=>$title,"type"=>$type,"distance"=>$distance,"formatted"=>$formatted,"status"=>"OK"));
                }

            }
            //return fully packaged data as a JSON object
            return json_encode($profileData, true);
        }

        //call main
        $main = main($location, $number, $measure);

        //return view with $main package
        return view('pages.found')->with(compact('main'));
    }
}


