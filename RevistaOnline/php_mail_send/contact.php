<?php  session_start(); ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>How to send mail using phpmiler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script><!--recaptcha-->
    <script>
      function enableSubmitButton() {
        document.getElementById("mySubmitBtn").disabled = false;

        
      }
    </script>

  </head>
  <body>

      
    <div class = "container mt-5">
        <div class = "card">
            <div class = "card-header">
                <h4>Send Mail</h4>
            </div>
            <div class = "card-body">

                <form action = "sendmail.php" method = "POST">
                    <div class = "mb-3">
                            <label for = "fullname" class = "form-label">Full Name</label>
                            <input type = "text" name = "full_name" id="fullname" class = "form-control" required>
                    </div>

                    <div class = "mb-3">
                        <label for = "email_address" class = "form-label">Email</label>
                        <input type = "email" name = "email" id="email_address" class = "form-control" required>
                    </div>

                    <div class = "mb-3">
                            <label for = "subject" class = "form-label">Subject</label>
                            <input type = "text" name = "subject" id="subject" class = "form-control" required>
                    </div>

                    <div class = "mb-3">
                            <label for = "message" class = "form-label">Message</label>
                            <textarea name = "message" id="message" class="form-control" rows="3" required></textarea>   
                    </div>


                    <div class = "mb-3">
                        <div class="g-recaptcha" data-sitekey="6LcndNUqAAAAAPRRaEMnSIFFO87C0ddX990KERw9" data-callback="enableSubmitButton"></div>
                    </div>

                    <div>
                        <button type = "submit" name="submitContact" id="mySubmitBtn" disabled="disabled" class="btn btn-primary">Send</button>
                        <button class="btn btn-primary" onclick="history.back()">Back</button>
                    </div>
                    
                    
                </form>
                
            </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
  document.addEventListener("DOMContentLoaded", function() {
          var messageText = "<?= $_SESSION['status'] ?? ''; ?>";
          console.log("Session Status:", messageText); 

          if (messageText.trim() !== '') {
            Swal.fire({
              title: "Success!",
              text: messageText,
              icon: "success"
            });

            <?php unset($_SESSION['status']); ?> 
          }
  });
</script>
  </body>
</html>