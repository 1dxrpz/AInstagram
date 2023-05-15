(function() {
	let loader = document.querySelector("#loader");
	window.addEventListener("load", () => {
		loader.style.opacity = '0';
		setTimeout(() => {
			loader.remove();
		}, 600);
	});
})();