<script language="JavaScript">
    var frmvalidator  = new Validator("contactform");
    frmvalidator.addValidation("identifier","req","Please provide your name");
    frmvalidator.addValidation("juan","req","Please provide your email");
    frmvalidator.addValidation("email","email",
      "Please enter a valid email address");
    </script>
