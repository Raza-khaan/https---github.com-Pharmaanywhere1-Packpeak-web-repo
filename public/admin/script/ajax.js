function get_sub_cat()
{
   var XMLHttpRequestObject = false;
		if(window.XMLHttpRequest)
		{
			XMLHttpRequestObject = new XMLHttpRequest;
		}
		else if(window.ActiveXObject)
		{
			XMLHttpRequestObject = new ActiveXObject;
		}
	  var category = document.getElementById('category').value;
		/*if(category =='')
		{
			alert('Please Select Category name.');
			return false;
		}*/
	var url = 'admin_functions.php?act=get_sub_cat&category='+category;
    //alert(url);
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var result = XMLHttpRequestObject.responseText ;
		//alert(result);
		var splitResults = result.split("###");
		
		//var strMatch = result.match(/Only/i);
		var data_type = document.getElementById('data_type').value;
		if(result!="")
		  {
			if(data_type=="update")
			  {
				  document.getElementById('sub_category_view').style.display="none";  
			  }
			document.getElementById('sub_category').style.display="block";   
			document.getElementById('show_sub_cat').innerHTML = splitResults[0];
			document.getElementById('sub_cat_count').value=splitResults[1];   
			//document.getElementById('show_product_type').innerHTML = result;
		  }
	}
	XMLHttpRequestObject.send();						
}	


function get_sub_sub_cat()
{
   var XMLHttpRequestObject = false;
		if(window.XMLHttpRequest)
		{
			XMLHttpRequestObject = new XMLHttpRequest;
		}
		else if(window.ActiveXObject)
		{
			XMLHttpRequestObject = new ActiveXObject;
		}
	  var category = document.getElementById('category').value;
	   var sub_cat = document.getElementById('sub_cat').value;
	  
		/*if(category =='')
		{
			alert('Please Select Category name.');
			return false;
		}*/
	var url = 'admin_functions.php?act=get_sub_sub_cat&category='+category+'&sub_cat='+sub_cat;
    //alert(url);
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var result = XMLHttpRequestObject.responseText ;
		//alert(result);
		var splitResults = result.split("###");
		
		//var strMatch = result.match(/Only/i);
		var data_type = document.getElementById('data_type').value;
		if(result!="")
		  {
			if(data_type=="update")
			  {
				  document.getElementById('sub_sub_category_view').style.display="none";  
			  }
			document.getElementById('sub_sub_category').style.display="block";   
			document.getElementById('show_sub_sub_cat').innerHTML = splitResults[0];
			document.getElementById('sub_sub_cat_count').value=splitResults[1];   
			//document.getElementById('show_product_type').innerHTML = result;
		  }
	}
	XMLHttpRequestObject.send();						
}	



function get_product_type()
{
   var XMLHttpRequestObject = false;
		if(window.XMLHttpRequest)
		{
			XMLHttpRequestObject = new XMLHttpRequest;
		}
		else if(window.ActiveXObject)
		{
			XMLHttpRequestObject = new ActiveXObject;
		}
	  var category = document.getElementById('category').value;
		/*if(category =='')
		{
			alert('Please Select Category name.');
			return false;
		}*/
	var url = 'admin_functions.php?act=get_product_type&category='+category;
    //alert(url);
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var result = XMLHttpRequestObject.responseText ;
		var splitResults = result.split("###");
		//alert(result);
		var data_type = document.getElementById('data_type').value;
		if(result!="")
		  {
			if(data_type=="update")
			  {
				  document.getElementById('product_type_view').style.display="none";  
			  }   
			document.getElementById('product_type').style.display="block";   
			document.getElementById('show_product_type').innerHTML = splitResults[0];
			document.getElementById('p_type_count').value=splitResults[1];   
		  }
	}
	XMLHttpRequestObject.send();						
}	


function check_user()
	{
	var XMLHttpRequestObject = false;
	if(window.XMLHttpRequest)
	{
		XMLHttpRequestObject = new XMLHttpRequest;
	}
	else if(window.ActiveXObject)
	{
		XMLHttpRequestObject = new ActiveXObject;
	}
	var user_id = document.getElementById('user_id');
	var id = user_id.value;
	var url = 'admin_functions.php?act=reset_user_password&user_id='+id;
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var msg = document.getElementById('message');
		var result = XMLHttpRequestObject.responseText;
		//alert(result);
		if(result != '')
		{
			msg.innerHTML = XMLHttpRequestObject.responseText;
		}
	}
	XMLHttpRequestObject.send(null);
}
function get_sub_cat1()
{
   var XMLHttpRequestObject = false;
		if(window.XMLHttpRequest)
		{
			XMLHttpRequestObject = new XMLHttpRequest;
		}
		else if(window.ActiveXObject)
		{
			XMLHttpRequestObject = new ActiveXObject;
		}
	  var category = document.getElementById('category').value;
		/*if(category =='')
		{
			alert('Please Select Category name.');
			return false;
		}*/
	var url = 'admin_functions.php?act=get_sub_cat1&category='+category;
    //alert(url);
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var result = XMLHttpRequestObject.responseText ;
		//alert(result);
		var splitResults = result.split("###");
		
		//var strMatch = result.match(/Only/i);
		var data_type = document.getElementById('data_type').value;
		if(result!="")
		  {
			if(data_type=="update")
			  {
				  document.getElementById('sub_category_view').style.display="none";  
			  }
			document.getElementById('sub_category').style.display="block";   
			document.getElementById('show_sub_cat').innerHTML = splitResults[0];
			document.getElementById('sub_cat_count').value=splitResults[1];   
			//document.getElementById('show_product_type').innerHTML = result;
		  }
	}
	XMLHttpRequestObject.send();						
}	


function get_sub_sub_cat()
{
   var XMLHttpRequestObject = false;
		if(window.XMLHttpRequest)
		{
			XMLHttpRequestObject = new XMLHttpRequest;
		}
		else if(window.ActiveXObject)
		{
			XMLHttpRequestObject = new ActiveXObject;
		}
	  var category = document.getElementById('category').value;
	   var sub_cat = document.getElementById('sub_cat').value;
	  
		/*if(category =='')
		{
			alert('Please Select Category name.');
			return false;
		}*/
	var url = 'admin_functions.php?act=get_sub_sub_cat&category='+category+'&sub_cat='+sub_cat;
    //alert(url);
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var result = XMLHttpRequestObject.responseText ;
		//alert(result);
		var splitResults = result.split("###");
		
		//var strMatch = result.match(/Only/i);
		var data_type = document.getElementById('data_type').value;
		if(result!="")
		  {
			if(data_type=="update")
			  {
				  document.getElementById('sub_sub_category_view').style.display="none";  
			  }
			document.getElementById('sub_sub_category').style.display="block";   
			document.getElementById('show_sub_sub_cat').innerHTML = splitResults[0];
			document.getElementById('sub_sub_cat_count').value=splitResults[1];   
			//document.getElementById('show_product_type').innerHTML = result;
		  }
	}
	XMLHttpRequestObject.send();						
}	



function get_product_type()
{
   var XMLHttpRequestObject = false;
		if(window.XMLHttpRequest)
		{
			XMLHttpRequestObject = new XMLHttpRequest;
		}
		else if(window.ActiveXObject)
		{
			XMLHttpRequestObject = new ActiveXObject;
		}
	  var category = document.getElementById('category').value;
		/*if(category =='')
		{
			alert('Please Select Category name.');
			return false;
		}*/
	var url = 'admin_functions.php?act=get_product_type&category='+category;
    //alert(url);
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var result = XMLHttpRequestObject.responseText ;
		var splitResults = result.split("###");
		//alert(result);
		var data_type = document.getElementById('data_type').value;
		if(result!="")
		  {
			if(data_type=="update")
			  {
				  document.getElementById('product_type_view').style.display="none";  
			  }   
			document.getElementById('product_type').style.display="block";   
			document.getElementById('show_product_type').innerHTML = splitResults[0];
			document.getElementById('p_type_count').value=splitResults[1];   
		  }
	}
	XMLHttpRequestObject.send();						
}	


function check_user()
	{
	var XMLHttpRequestObject = false;
	if(window.XMLHttpRequest)
	{
		XMLHttpRequestObject = new XMLHttpRequest;
	}
	else if(window.ActiveXObject)
	{
		XMLHttpRequestObject = new ActiveXObject;
	}
	var user_id = document.getElementById('user_id');
	var id = user_id.value;
	var url = 'admin_functions.php?act=reset_user_password&user_id='+id;
	XMLHttpRequestObject.open("POST", url);
	XMLHttpRequestObject.onreadystatechange = function()
	{
		var msg = document.getElementById('message');
		var result = XMLHttpRequestObject.responseText;
		//alert(result);
		if(result != '')
		{
			msg.innerHTML = XMLHttpRequestObject.responseText;
		}
	}
	XMLHttpRequestObject.send(null);
}