 
 function ValidatePieces()
   {
    if(document.getElementById('pieces').value=="")
     {
	   document.getElementById('pieces').focus();
	   alert("Please Add Pieces.");
	   return false;
	 }
	 if(isNaN(document.getElementById('pieces').value))
     {
	   document.getElementById('pieces').focus();
	   alert("Please Add Pieces in Numeric.");
	   return false;
	 }
   }
   
  function VlaidateCategory()
   {
    if(document.getElementById('category').value=="")
     {
	   document.getElementById('category').focus();
	   alert("Please Write Category Name.");
	   return false;
	 }
   }  
   
   function VlaidateMianCategory()
   {
    if(document.getElementById('name').value=="")
     {
	   document.getElementById('name').focus();
	   alert("Please Write Category Name.");
	   return false;
	 }
	 if(document.getElementById('amount').value=="")
     {
	   document.getElementById('amount').focus();
	   alert("Please Write Amount.");
	   return false;
	 }
	 if(isNaN(document.getElementById('amount').value))
     {
	   document.getElementById('amount').focus();
	   alert("Please Write Amount in Numeric.");
	   return false;
	 }
   }
   
   function VlaidateProduct()
   {
    if(document.getElementById('category').value=="")
     {
	   document.getElementById('category').focus();
	   alert("Please Select Category Name.");
	   return false;
	 }
	if( document.getElementById('sub_cat_count').value>0)
	  {
		if(document.getElementById('sub_cat').value=="")
		 {
		   document.getElementById('sub_cat').focus();
		   alert("Please Select Sub Category Name.");
		   return false;
		 } 
	  }
	if( document.getElementById('p_type_count').value>0)
	  {  
		if(document.getElementById('type').value=="")
		 {
		   document.getElementById('type').focus();
		   alert("Please Select Product Type.");
		   return false;
		 }
	  }
	if(document.getElementById('name').value=="")
     {
	   document.getElementById('name').focus();
	   alert("Please Add Product Name.");
	   return false;
	 }
	if(document.getElementById('code').value=="")
     {
	   document.getElementById('code').focus();
	   alert("Please Add Product Code.");
	   return false;
	 } 
	if(document.getElementById('weight').checked==false && document.getElementById('pieces').checked==false)
     {
	   document.getElementById('weight').focus();
	   alert("Please Select Product Packaging Type.");
	   return false;
	 } 
    if(document.getElementById('weight').checked==true)
     {	 
	   if(document.getElementById('weight_unit').value=="")
		 {
		   document.getElementById('weight_unit').focus();
		   alert("Please Select Weight Unit.");
		   return false;
		 } 
	 }
	if(document.getElementById('pieces').checked==true)
     {	 
	   if(document.getElementById('pieces_unit').value=="")
		 {
		   document.getElementById('pieces_unit').focus();
		   alert("Please Select Pieces Unit.");
		   return false;
		 } 
	 } 
	if(document.getElementById('price').value=="")
     {
	   document.getElementById('price').focus();
	   alert("Please Add Product Price.");
	   return false;
	 } 
	if(isNaN(document.getElementById('price').value))
     {
	   document.getElementById('price').focus();
	   alert("Please Add Product Price in Numeric Only.");
	   return false;
	 } 
	if(document.getElementById('details').value=="")
     {
	   document.getElementById('details').focus();
	   alert("Please Add Product Details.");
	   return false;
	 }  
   }
 function VlaidateLogin()
   {
    if(document.getElementById('user_name').value=="")
     {
	   document.getElementById('user_name').focus();
	   alert("Please Type username First.");
	   return false;
	 }
	if(document.getElementById('password').value=="")
     {
	   document.getElementById('password').focus();
	   alert("Please Type password First.");
	   return false;
	 }
   }
   
 function VlaidateForgotPass()
   {
	var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;   
    if(document.getElementById('email').value=="")
     {
	   document.getElementById('email').focus();
	   alert("Please Type Email-Id First.");
	   return false;
	 }
	else 
	  {
		if(!pattern.test(document.getElementById('email').value)){     
	    document.getElementById('email').focus();
		alert("Please Eneter Correct Email - Id.");   
		return false;
         }  
	  }
   }
   
  
   
  function VlaidatePassword()
   {
    if(document.getElementById('old_pass').value=="")
     {
	   document.getElementById('old_pass').focus();
	   alert("Please Type Old Password.");
	   return false;
	 }
	if(document.getElementById('new_pass').value=="")
     {
	   document.getElementById('new_pass').focus();
	   alert("Please Type New Password.");
	   return false;
	 }
	if(document.getElementById('c_pass').value=="")
     {
	   document.getElementById('c_pass').focus();
	   alert("Please Type Confirm New Password.");
	   return false;
	 }
	if(document.getElementById('new_pass').value!=document.getElementById('c_pass').value)
     {
	   document.getElementById('c_pass').focus();
	   alert("Confirm and  New Password Doesn't Match.");
	   return false;
	 } 
   } 
  
  function SelectPackage()
   {
	 if(document.getElementById('weight').checked==true)
	   {
	    document.getElementById('weight_box').style.display='block';
		document.getElementById('pieces_box').style.display='none';
	   }
	 else
	  {
	   document.getElementById('weight_box').style.display='none';
	   document.getElementById('pieces_box').style.display='block';
	  // document.getElementById('email').value='';
	  }
   }
   
   function DeleteConfirm(id,url)
    {
	 var answer = confirm('Are You Sure Want To Delete This Row?')	
	  if(answer)	
	    {
			window.location.href='action/'+url+'?subview=delete&id='+id;
			return true;
	    }
	  else
	   {
		 return false;   
	   }
    }
	

function ValidateUser()
    {
	  var msg = document.getElementById('message').innerHTML;
	  var user = document.getElementById('user_id').value;
	 // reWhiteSpace = new RegExp(/\s+$/);
	    reWhiteSpace = new RegExp(/\s+/);
	  
	  if (reWhiteSpace.test(user)) {
		  alert("There should be No Spaces");
		  document.getElementById('user_id').focus();
		  return false;
		}
	  if(msg!=' ' || document.getElementById('user_id').value=='')
	    {
		  //alert(document.getElementById('message').innerHTML);
		  alert("Please check User Id.");
		  document.getElementById('user_id').focus();
		  return false;
		}
	}	

function GeneralSettings()
    {
	  	    reWhiteSpace = new RegExp(/\s+/);
	var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;   

	  if(document.getElementById('time').value=="")
	    {
			alert("Please Select Time.");
		  document.getElementById('time').focus();
		  return false;
		}
     
	 if(document.getElementById('am_pm').value=="")
	    {
			alert("Please Select Am/Pm.");
		  document.getElementById('am_pm').focus();
		  return false;
		}
	
	if(document.getElementById('title').value=="")
	    {
			alert("Please Enter Website Title.");
		  document.getElementById('title').focus();
		  return false;
		}
	  
	if(document.getElementById('meta_description').value=="")
	    {
			alert("Please Enter Meta Description.");
		  document.getElementById('meta_description').focus();
		  return false;
		} 
	if(document.getElementById('meta_keyword').value=="")
	    {
			alert("Please Enter Meta Keyword.");
		  document.getElementById('meta_keyword').focus();
		  return false;
		} 
		
	if(document.getElementById('email_id').value=="")
	    {
			alert("Please Enter Admin's Email-Id.");
		  document.getElementById('email_id').focus();
		  return false;
		} 	
	if(document.getElementById("email_id").value!="")
	  {
		if(!pattern.test(document.getElementById('email_id').value)){     
			document.getElementById('email_id').focus();
			alert("Please Eneter Correct Email - Id.");   
			return false;
			 }  
	  }
	  if(document.getElementById('listing').value=="")
	    {
			alert("Please Enter Numbers of Product Listing on a page.");
		  document.getElementById('listing').focus();
		  return false;
		} 
	  if(isNaN(document.getElementById('listing').value)) {
		  alert("There should be Only Numbers");
		  document.getElementById('listing').focus();
		  return false;
		}	
	  if(reWhiteSpace.test(document.getElementById('listing').value)) {
		  alert("There should be No Spaces");
		  document.getElementById('listing').focus();
		  return false;
		}	

	
	  if(document.getElementById('quant').value=="")
	    {
			alert("Please Enter Quantity, User can purchase of a Product at a time.");
		  document.getElementById('quant').focus();
		  return false;
		} 
	 if(isNaN(document.getElementById('quant').value)) {
		  alert("There should be Only Numbers");
		  document.getElementById('quant').focus();
		  return false;
		}	
	  if(reWhiteSpace.test(document.getElementById('quant').value)) {
		  alert("There should be No Spaces");
		  document.getElementById('quant').focus();
		  return false;
		}	
	}
