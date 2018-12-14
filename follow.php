<?php
    session_start();
?>

<?php
include 'db_functions.php';
    $promID = $_POST['name'];
    $conn = dbConnect();

    if( $conn && $follow = mysqli_query($conn, "SELECT * FROM Followed_By WHERE PromoterID={$promID} AND FanID={$_SESSION['userID']}"))
    {
        if ( mysqli_num_rows($follow) > 0 )
            echo "<em>Followed</em>";
        else
        {
           $query = "INSERT INTO Followed_By (FanID, PromoterID) VALUES ({$_SESSION['userID']},{$promID}) ";
           if($results = mysqli_query($conn, $query)){
               echo"<em>Now Following<em>";
               header('Location: followPromoters.php');
           }else{
            echo "ERROR: Could not execute $query." . mysqli_error($conn);
           }
        }
    }
    else{
        echo "ERROR: Could not execute." . mysqli_error($conn);
    }
?>