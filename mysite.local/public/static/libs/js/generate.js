
import { HfInference } from 'https://cdn.jsdelivr.net/npm/@huggingface/inference@1.8.0/+esm';
import { createRepo, commit, deleteRepo, listFiles } from "https://cdn.jsdelivr.net/npm/@huggingface/hub@0.5.0/+esm";

const HF_ACCESS_TOKEN = "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";
const inference = new HfInference(HF_ACCESS_TOKEN);

let generate_prompt = document.querySelector(".generate_prompt");
let generate_button = document.querySelector(".generate_button");
let download_button = document.querySelector(".download_button");
let generate_loading = document.querySelector(".generate_loading");
let generate_loading_text = document.querySelector(".generate_loading_text");
var generated_image = document.querySelector('.generated_image');

download_button.addEventListener("click", () => {
	downloadImage(generated_image.src);
});

generate_button.addEventListener("click", () => {
	GenerateImage(generate_prompt.value, document.querySelector(".generate_model").value);
});

async function GenerateImage(prompt, model) {
	generate_button.setAttribute("disabled", true);
	download_button.setAttribute("disabled", true);
	generate_loading.setAttribute("data-hidden", false);
	generate_loading_text.setAttribute("data-hidden", true);

	var response = await inference.textToImage({
		inputs: prompt,
		model: model,
		parameters: {
			//negative_prompt: 'blurry',
		}
	});
	var objectURL = URL.createObjectURL(response);
	generated_image.src = objectURL;
	generate_button.removeAttribute("disabled");
	download_button.removeAttribute("disabled");
	generate_loading.setAttribute("data-hidden", true);
}

async function downloadImage(imageSrc) {
	const image = await fetch(imageSrc)
	const imageBlog = await image.blob()
	const imageURL = URL.createObjectURL(imageBlog)

	const link = document.createElement('a')
	link.href = imageURL
	link.download = 'image file name here'
	document.body.appendChild(link)
	link.click()
	document.body.removeChild(link)
}
