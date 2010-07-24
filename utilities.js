/* Function for removing array duplicates - http://www.martienus.com/code/javascript-remove-duplicates-from-array.html */
Array.prototype.unique = function () {
	var r = new Array();
	o:for(var i = 0, n = this.length; i < n; i++)
	{
		for(var x = 0, y = r.length; x < y; x++)
		{
			if(r[x]==this[i])
			{
				continue o;
			}
		}
		r[r.length] = this[i];
	}
	return r;
}
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}


function distortionPopup() {
	window.open("assuming-popup.html","Distortion Explanations","height=500,width=530,scrollbars=1");
}
