<?php
require_once 'config/config.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-5">

    <div class="row">
  
        <div class="col-md-6">
            <h1>Bine ai venit la CinemaBibanu</h1>
            <p class="lead">
                Bine ai venit pe pagina cinematografului dedicat marelui actor si profesor, Dem Rădulescu.
            </p>
            
           
            <div class="row align-items-center mt-4">
                
              
                <div class="col-6"> 
                    <img src="images/dem_radulescu_portrait.png" 
                         alt="Portret Dem Rădulescu" 
                         class="img-fluid rounded shadow-sm">
                </div>
                
                
                <div class="col-6">
                    <div class="card p-3 shadow-sm">
                        <h6 class="mb-2">Rezervă un bilet la film</h6>
                        <ul class="mb-0 small">
                            <li>Creezi un cont</li>
                            <li>Intri pe pagina de rezervări</li>
                            <li>Alegi un film</li>
                            <li>Vizionare placuta!</li>
                        </ul>
                    </div>
                </div>
                
            </div>
         

            <a href="pages/movies.php" class="btn btn-primary mt-4">Vezi filme</a>
        </div>


        <div class="col-md-6">
            
            <div class="card p-4">
                <h5>Bucurati-va de filme romanesti clasice!</h5>
                 <p> Creeaza un cont si rezerva chiar acum locul la film </p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
