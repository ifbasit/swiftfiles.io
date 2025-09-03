<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title></title>
		<meta name="description" content="Casino websites reviews - Privacy Policy">
		<meta name="author" content="goldTemp">
		
		<meta property="og:title" content="Casino websites reviews - Privacy Policy">
		<meta property="og:type" content="website">
		<meta property="og:url" content="">
		<meta property="og:description" content="Casino websites reviews - Privacy Policy">
		<meta property="og:image" content="image.png">
		
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
		
		<link rel="icon" href="./assets/favicon.ico">
		<link rel="icon" href="./assets/favicon.svg" type="image/svg+xml">
		<link rel="apple-touch-icon" href="./assets/apple-touch-icon.png">
		
		<link rel="stylesheet" href="./assets/style.css">

		<style></style>

	
	</head>
	
	<body>
		
		
		<div class="row simpleText">
			<div class="col-12">
				<div class="divider"><span></span><span><h1 class="text-center">Disclaimer</h1></span><span></span></div>
				
				


		<p class="text-center">
This site serves as a valuable resource for the horse racing community, providing reviews of trustworthy and secure gambling platforms, along with related resources and advice. Our aim is to assist horse racing enthusiasts in making informed choices regarding their online gaming activities. We diligently verify age (21+), ensure the fairness of horse racing software, and support initiatives to combat gambling addiction.
Please note that this site is intended solely for informational purposes and does not offer any actual horse racing for bet; the responsibility for gaming lies with the individual casinos. Users must be of legal age (over 21 years old or as required by the laws of their country) to register for an account and participate in online horse racing.

This site reserves the right to change its content at any time without prior notice. However, users should always keep in mind that engaging in online horse racing involves inherent risks and should proceed with caution.
			</p>
			<p class="text-center"><strong> External Links Disclaimer </strong></p>

			<p class="text-center">
his site contains links to external websites. We do not represent any online gaming sites and assume no liability for the content or use of any site accessed through this site. The inclusion of links to other websites does not imply our endorsement or responsibility for any inaccuracies or deficiencies in their content.
We are not responsible for the content of these external sites. Under no circumstances shall we be liable for any loss, damage, obligation, or expense incurred as a result of using these sites.

It is important to note that some links on this site may be affiliate links. We may receive compensation or commissions from companies whose services are advertised through these links. However, this does not affect our evaluation process of horse racing sites or the creation of our reviews.</p>


            <p class="text-center"><strong>Responsible Gambling</strong></p>

            <p class="text-center">
Gambling has become widely accepted across various cultures. However, players must acknowledge the inherent risks of both winning and losing money while participating in such activities. Additionally, online gambling carries the risk of developing gambling addiction or other related issues.
We strongly encourage individuals to remain aware of these risks and to take appropriate measures if they encounter such challenges. Responsible gambling involves not wagering money that is essential for living expenses and recognizing when to stop gambling, even in the face of losses. It is crucial to avoid borrowing money for gambling and to refrain from gambling when fatigued or feeling depressed.

If you suspect you may have a gambling problem or addiction, we urge you to seek help from specialized support services.</p>
				
			</div>
		</div>
		<?php include 'footer.php'; ?>

		<div id="particles-js"></div>
	</body>
	<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
	<script>
		particlesJS("particles-js", {
			"particles": {
				"number": {
					"value": 400,
					"density": {
						"enable": true,
						"value_area": 3000
					}
				},
				"color": {
					"value": "#fc0000"
				},
				"shape": {
					"type": "circle",
					"stroke": {
						"width": 0,
						"color": "#000000"
					},
					"polygon": {
						"nb_sides": 3
					},
					"image": {
						"src": " index/assets/img/github.svg",
						"width": 100,
						"height": 100
					}
				},
				"opacity": {
					"value": 0.5,
					"random": true,
					"anim": {
						"enable": false,
						"speed": 1,
						"opacity_min": 0.1,
						"sync": false
					}
				},
				"size": {
					"value": 2,
					"random": true,
					"anim": {
						"enable": true,
						"speed": 5,
						"size_min": 0,
						"sync": false
					}
				},
				"line_linked": {
					"enable": false,
					"distance": 500,
					"color": "#ffffff",
					"opacity": 0.4,
					"width": 2
				},
				"move": {
					"enable": true,
					"speed": 7.81,
					"direction": "top",
					"random": true,
					"straight": false,
					"out_mode": "out",
					"bounce": false,
					"attract": {
						"enable": false,
						"rotateX": 600,
						"rotateY": 1200
					}
				}
			},
			"interactivity": {
				"detect_on": "canvas",
				"events": {
					"onhover": {
						"enable": false,
						"mode": "bubble"
					},
					"onclick": {
						"enable": false,
						"mode": "repulse"
					},
					"resize": true
				},
				"modes": {
					"grab": {
						"distance": 400,
						"line_linked": {
							"opacity": 0.5
						}
					},
					"bubble": {
						"distance": 400,
						"size": 4,
						"duration": 0.3,
						"opacity": 1,
						"speed": 3
					},
					"repulse": {
						"distance": 200,
						"duration": 0.4
					},
					"push": {
						"particles_nb": 4
					},
					"remove": {
						"particles_nb": 2
					}
				}
			},
			"retina_detect": true
		});
	</script>
	
	<!-- Menu script -->
	<script>
		// Toggle to show and hide navbar menu
		const navbarMenu = document.getElementById("menu");
		const burgerMenu = document.getElementById("burger");
		
		burgerMenu.addEventListener("click", () => {
		  navbarMenu.classList.toggle("is-active");
		  burgerMenu.classList.toggle("is-active");
		});
		
		// Toggle to show and hide dropdown menu
		const dropdown = document.querySelectorAll(".dropdown");
		
		dropdown.forEach((item) => {
		  const dropdownToggle = item.querySelector(".dropdown-toggle");
		
		  dropdownToggle.addEventListener("click", () => {
			const dropdownShow = document.querySelector(".dropdown-show");
			toggleDropdownItem(item);
		
			// Remove 'dropdown-show' class from other dropdown
			if (dropdownShow && dropdownShow != item) {
			  toggleDropdownItem(dropdownShow);
			}
		  });
		});
		
		// Function to display the dropdown menu
		const toggleDropdownItem = (item) => {
		  const dropdownContent = item.querySelector(".dropdown-content");
		
		  // Remove other dropdown that have 'dropdown-show' class
		  if (item.classList.contains("dropdown-show")) {
			dropdownContent.removeAttribute("style");
			item.classList.remove("dropdown-show");
		  } else {
			// Added max-height on active 'dropdown-show' class
			dropdownContent.style.height = dropdownContent.scrollHeight + "px";
			item.classList.add("dropdown-show");
		  }
		};
		
		// Fixed dropdown menu on window resizing
		window.addEventListener("resize", () => {
		  if (window.innerWidth > 992) {
			document.querySelectorAll(".dropdown-content").forEach((item) => {
			  item.removeAttribute("style");
			});
			dropdown.forEach((item) => {
			  item.classList.remove("dropdown-show");
			});
		  }
		});
		
		// Fixed navbar menu on window resizing
		window.addEventListener("resize", () => {
		  if (window.innerWidth > 992) {
			if (navbarMenu.classList.contains("is-active")) {
			  navbarMenu.classList.remove("is-active");
			  burgerMenu.classList.remove("is-active");
			}
		  }
		});
	</script>
	
	<!-- Sets the .smallLabel div to have a min-width based on the text inside it -->
	<script>
		// Get all elements with the class .smallLabel
		var smallLabels = document.querySelectorAll('.smallLabel');
		
		// Loop through each .smallLabel element
		smallLabels.forEach(function(element) {
			// Get the width of the .smallLabel element
			var labelWidth = element.offsetWidth;
		
			// Add a couple of extra pixels to the width (just in case)
			var minWidth = labelWidth + 4;
		
			// Set the minimum width of the .smallLabel element
			element.style.minWidth = minWidth + 'px';
		});
	</script>
	
</html>