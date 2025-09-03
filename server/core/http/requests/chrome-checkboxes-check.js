var history_item = document.querySelector("#history-app").shadowRoot.querySelector("#history").shadowRoot.querySelectorAll("history-item");

for(var i = 0; i < history_item.length; i++){
	let shadow_root = history_item[i].shadowRoot.querySelector("#checkbox").shadowRoot.querySelector("#checkbox").click()
	console.log(shadow_root)
}
