<?php 
    require_once("inc/header.php");
    require_once("inc/navigation.php");


    if(isset($_GET['homepage']))
    {
        require_once("inc/homepage.php");
    }else if(isset($_GET['addElectionPage']))
    {
        require_once("inc/add_elections.php");
    }else if(isset($_GET['addCandidatePage']))
    {
        require_once("inc/add_candidates.php");
    }else if(isset($_GET['viewResult']))
    {
        require_once("inc/viewResults.php");
    }else if(isset($_GET['edit_election']))
    {
        require_once("inc/edit_election.php");
    }else if(isset($_GET['download']))
    {
        require_once("inc/download.php");
    }else if(isset($_GET['delete_candidate']))
    {
        require_once("inc/delete_candidate.php");
    }


?>


<?php 
    require_once("inc/footer.php");
?>