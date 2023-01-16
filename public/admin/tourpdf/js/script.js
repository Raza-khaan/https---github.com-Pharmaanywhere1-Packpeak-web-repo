function generateDocumentPdf(){ 
var HTML_Width = $("#tblCustomers").width();
var HTML_Height = $("#tblCustomers").height();
var top_left_margin = 15;
var PDF_Width = HTML_Width+(top_left_margin*2);
var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
var canvas_image_width = HTML_Width;
var canvas_image_height = HTML_Height;
var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;
html2canvas(document.getElementById("tblCustomers"),{allowTaint:true}).then(function(canvas) {
canvas.getContext('2d');
// console.log(canvas.height+"  "+canvas.width);
var imgData = canvas.toDataURL("image/jpeg", 1.0);
var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
for (var i = 1; i <= totalPDFPages; i++) { 
	pdf.addPage(PDF_Width, PDF_Height);
	pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin),canvas_image_width,canvas_image_height);
}		
// pdf.save("Tour-Document.pdf"); 
// console.log(pdf.output('datauri'))
// console.log(imgData)
// var doc = pdf.output('blob');
// var formData = new FormData();
// formData.append('pdf', doc);


var doc = pdf.output();
    var data = new FormData();
    // var new = _token: "{{ csrf_token() }}"; 
    // data.append("data" , doc);
    // console.log(doc)
    var Alldata = {formData:doc, _token: "{{ csrf_token() }}"};
       
    var xhr = new XMLHttpRequest();
    xhr.open( 'post', 'http://localhost/taxi/create_tour_pdf', true );
    xhr.send(Alldata);

console.log(pdf)
console.log('Hi Pradeep')



});
};


$("body").on("click", "#btnExport", function (e) {
e.preventDefault();
window.scrollTo(0,0);
var m=generateDocumentPdf(); 
// console.log("Hi Pradeep")

}).on('blur','[contentEditable]',function(){
	
var newtxt=$(this).text();
if(!newtxt){
$(this).text('-');
}
});