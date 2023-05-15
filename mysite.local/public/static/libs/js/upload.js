let image_input = document.querySelector("#post_upload_form_ImageURL");
let image_preview = document.querySelector(".image_preview");
let image_preview_wrapper = document.querySelector(".image_preview_wrapper");

const isValidUrl = urlString=> {
  	var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
    '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
  return !!urlPattern.test(urlString);
}

image_input.addEventListener("input", () => {
	if (isValidUrl(image_input.value)) {
		image_preview_wrapper.setAttribute("data-hidden", false);
		var newImg = new Image;
		newImg.onload = function() {
		    image_preview.src = this.src;
		}
		newImg.src = image_input.value;
	} else {
		image_preview_wrapper.setAttribute("data-hidden", true);
	}
});