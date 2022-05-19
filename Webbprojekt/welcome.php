<?php
    session_start();
    
    if(!isset($_SESSION["username"])){
        header("location:index.php");
        exit(); 
    }    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>

<body class="bigDiv3">
    <div class="allDiv w-100 vh-100 d-flex flex-column jusify-content-center align-items-center text-white">
        <div class="container mb-5 p-0 w-auto h-auto d-flex justify-content-center border border-secondary rounded">
            <div class="text-center">
                <h1>Welcome <?php echo $_SESSION["username"];?></h1>
                <a href="logout.php">Log Out?</a>
            </div>
        </div>

        <input type="text" value="" id="cityName">
        <div class="widgetContainer mb-5 p-0 d-flex flex-column">
            <!--Container för första raden med minsta och högsta temperaturen-->
            <div class="container-fluid text-white p-0 m-0">
                <div class="row p-0">
                    <div class="col-3 text-left">
                        <h4 class="minTemp font-weight-bold"></h4>
                    </div>
                    <div class="col-6 text-center">
                        <h1 class="city font-weight-bold"></h1>
                    </div>
                    <div class="col-3 text-right">
                        <h4 class="maxTemp font-weight-bold"></h4>
                    </div>
                </div>
            </div>

            <!--En div för bilden med det nuvarande vädret och temperaturen-->
            <div class="imageContainer d-flex justify-content-center align-items-center text-white m-0 p-0">
                <div>
                    <img class="weatherIcon" src="">
                    <h1 class="currentTemp font-weight-bold text-center"></h1>
                </div>
            </div>

            <!--Container för fuktighet och vindhastighet-->
            <div class="container-fluid w-100 h-auto text-white p-0 m-0">
                <div class="row m-0 p-0">
                    <div class="col-6">
                        <h4 class="hum font-weight-bold text-left"></h4>
                    </div>
                    <div class="col-6">
                        <h4 class="windSpd font-weight-bold text-right"></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="container vh-100 vh-100 m-0 p-0 formDiv d-flex justify-content-center align-items-center">
            <form class="text-center h-100 w-100" action="" method="POST" enctype="multipart/form-data">
                <div class="fileDiv h-100 w-100 border border-dark">
                    <input name="files[]" class="h-100 w-100" type="file" multiple="multiple"></input>
                </div>
                <input class="submitBtm" type="submit" name="submit" value="Upload"></input>
            </form>
        </div>
    </div>
        <script>
            $(document).keypress(function (e) {
                if (e.which == 13) {
                    let cityName = $("#cityName").val();
                    $.get("https://api.openweathermap.org/data/2.5/weather?q=" + cityName +
                        "&units=metric&appid=2e5a2d4fdb43c807501fdf50356b67e7",
                        function (data) {
                            //Lägger information i klasserna som exempelvis temperaturen, vindhastighet och bild för vädret. 
                            $("h4.minTemp").text("Min: " + Math.floor(data.main.temp_min) + "°C");
                            $("h1.city").text(data.name);
                            $("h4.maxTemp").text("Max: " + Math.floor(data.main.temp_max) + "°C");
                            $("h4.hum").text("Humidity: " + Math.floor(data.main.humidity) + "%");
                            $("h4.windSpd").text("Wind speed: " + Math.floor(data.wind.speed) +
                                " km/h");
                            $(".weatherIcon").attr("src", "https://openweathermap.org/img/wn/" +
                                data.weather[0].icon +
                                "@2x.png");
                            $("h1.currentTemp").text(Math.floor(data.main.temp) + "°C");
                        });
                }
            });
        </script>
</body>

</html>

<?php
    if(isset($_POST["submit"])){

        $filesCount = count($_FILES["files"]["name"]);

        for($i = 0; $i < $filesCount; $i++){
            $filetype = strtolower(pathinfo($_FILES["files"]["name"][$i], PATHINFO_EXTENSION));
            
        $mix_folder = "mix/".basename($_FILES["files"]["name"][$i]);
    
        if ($filetype == "jpg" || $filetype == "png" || $filetype == "svg" || $filetype == "gif" || $filetype == "docx" || $filetype == "txt"  || $filetype == "mp3"  || $filetype == "wav"){
            move_uploaded_file($_FILES["files"]["tmp_name"][$i], $mix_folder);
            echo "<script>alert(Image successfully uploaded)</script>";
        }
        else if (file_exists($mix_folder)){
            echo "<script>alert(File exists)</script>";
            continue;
        }
        else if ($_FILES["files"]["error"][$i] == 1){
            echo "<script>alert(File exceeds filesize)</script>";
            break;
        }
        else{
            echo "<script>alert(Invalid file type)</script>";
            continue;
        }
    }
    }
?>