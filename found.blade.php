@extends('layouts.app')

@section('content')
    {{-- Section One Begins --}}
    <div class="team-01 container-fluid">
        @include('layouts.includes.navbar')
        <div class="flex-half-center" style="margin-top: 40px;">
            <h1 class="text-white text-playfair underline-light-blue pb-2">Find a Loan Officer or Branch</h1>
        </div>
    </div>

    {{-- Section Two Begins --}}
    <?php
    $loc = $_GET['location'];
    $num = $_GET['number'];
    $meas = $_GET['measure'];
    ?>

    <div class="about-3 container-fluid">
        <div class="text-center pt-4">
            <div class="container">
                <div class="form-group">
                    <form method="get" action="/found">
                        <div class="row ">
                            <div class="col-md-4 p-2 text-playfair text-white">
                                <h4 class="p-1">Your Location</h4><br>
                                <input type="text" class="form-control my-2" name="location" value="<?php echo $loc; ?>" placeholder="Example: 1600 Pennsylvania Ave">
                            </div>
                            <div class="col-md-4 p-2 text-playfair text-white">
                                <h4 class="p-1">How far out you'er welling to look</h4><br>
                                <input type="number" class="form-control my-2" name="number" value="<?php echo $num; ?>" placeholder="Example: 50" min="0">

                            </div>
                            <div class="col-md-4 p-2 text-playfair text-white">
                                <h4 class="p-1">Miles or Kilometers</h4><br>
                                <select name="measure" class="form-control my-2">
                                    <option value="ML"<?php if ($meas == "ML") { echo "selected"; } ?>>Miles</option>
                                    <option value="KM" <?php if ($meas == "KM") { echo "selected"; } ?>>Kilometers</option>
                                </select>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 p-2 text-playfair text-white">
                                <button type="submit" class="text-uppercase light-blue-button mt-5">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Two Ends --}}

    <div class="team-04 container-fluid">
        <div class="text-center pt-4">
            <div class="container">
            <?php  //echo decode main and set it to profile;
            $Profile = json_decode($main, true);
            if (isset($Profile)) {
                $count = 0;

                if (empty($Profile) ) { ?>
                <div class='col-sm-12 mt-5 foo foo-2 bold'>
                    <h3 class="text-center pt-4"><b>No Branch  Officers were found within <?php echo $num; ?> <?php echo $meas; ?>  of <?php echo $loc; ?> : Try Seattle or New York</b></h3>
                </div>
                <?php
                }

                foreach($Profile as $item) { //foreach element in $Profile
                //check status

                $status = $item['status'];
                if ($status == "Error" or empty($Profile) ) { ?>
                            <div class='col-sm-12 mt-5 foo foo-2 bold'>
                                <h3 class="text-center pt-4"><b><?php echo $loc; ?> was no a valid entry: Try Seattle or New York</b></h3>
                            </div>
                <?php break; //breaks evething past this point if error
                } else {
                $id = $item['id'];
                $address = $item['address'];
                $name = $item['name'];
                $image = $item['image'];
                $title = $item['title'];
                $type = $item['type'];
                $distance = $item['distance'];
                }

                $count += 1;

                if (($count % 2) == 0) {
                    $even = true;
                } else {
                    $even = false;
                }

                if (($count % 4) == 1 or ($count % 4) == 2) {
                    $imageSec = true;
                } else {
                    $imageSec = false;
                }

                if (($count % 8) == 1 or ($count % 8) == 3 or ($count % 8) == 6 or ($count % 8) == 0) {
                    $blue = true;
                } else {
                    $blue = false;
                }
                ?>

                <?php if ($even == 0) { ?>
                <!-- profile row <?php echo $count; ?> starts -->
                    <div class="row bold">
                        <?php } //elseif ($status != "Error") { ?>
                        <div class="col-sm-6 pt-4 foo foo-2 bold">
                            <div class="card" style="overflow: hidden;">
                                <div class="card-block <?php if ($blue == 1) { ?>profileblue<?php } else {?>profileorange<?php } ?>">
                                    <div class="row p-0">
                                        <div class="col-sm-6 col <?php if ($imageSec == 1) { ?>order-2 <?php } ?> profile p-0">
                                            <a data-toggle="modal" data-target="#modal" >
                                                <div class="profile p-0">
                                                    <img class="profileImage" src="/images/branch/lake-oswego/profile/<?php echo $image; ?>.png" alt="image">
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 col <?php if ($imageSec == 1) { ?>order-1<?php } ?> profile">
                                            <div class="p-3 text-white">
                                                <h5 class="text-uppercase"><?php echo $name; ?></h5>
                                                <p><i><?php echo $title; ?></i></p>
                                                <p><i><?php echo "You are <b>$distance</b> $type away."; ?></i></p>
                                                <p><i><?php echo "Address: $address."; ?></i></p>
                                                <p><i><?php echo "Contacts: "; ?></i></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <?php
                            //}
                            if ($even == 1) { ?>
                    </div>
            <!-- profile row <?php echo ($count); ?> ends -->
                <?php } } ?>


                <?php if (($count % 2) == 1) {
                    //if count ends on an odd number close the row
                    echo "</div>";
                }
                ?>
                <br>

                <?php } ?>

            </div>
        </div>
    </div>

@endsection