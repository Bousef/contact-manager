document.getElementById("importForm").addEventListener("submit", function(event) {
	event.preventDefault(); // NEEDED to prevent the submit button reloading the page.

	// Set file to the input file.
	const file = document.getElementById("fileInput").files[0];

	// Find file type and switch to the proper parse method
	const fileType = file.name.toLowerCase();

	// Parse CSV (works with both google and outlook formats)
	if(fileType.endsWith(".csv")) { // Use imported js library to parse csv file.
		Papa.parse(file, {
			header: true,
			complete: function(results) {
				// console.log("csv parse started", results.data);
				
				// Remove blank rows/objects (usually just at the end)
				var inputData = results.data.filter(row => {
					return Object.values(row).some(value => value !== null && value !== "");
				});

				// Rename object fields to the project format and discard unused data. Also create user_id and req_type fields.
				const outputObj = inputData.map(row => {
					const reformattedObj = {
						req_type: "create",
						user_id: null,
						first_name: row["First Name"] || null,
						last_name: row["Last Name"] || null,
						phone_number: row["Phone 1 - Value"] || row["Primary Phone"] || row["Mobile Phone"] || row["Home Phone"] || row["Home Phone 2"] || row["Other Phone"] || row["Business Phone"] || row["Business Phone 2"] || row["Assistant's Phone"] || row["Car Phone"] || row["Radio Phone"] || row["Company Main Phone"] || null,
						email: row["E-mail Address"] || row["E-mail 2 Address"] || row["E-mail 3 Address"] || null,
						img_url: row["Photo"] || null
					};
					return reformattedObj;
				});

				const outputREMOVEME = JSON.stringify(outputObj, null, 2);
	
				// REMOVE AND SEND TO SERVER INSTEAD
				document.getElementById("output").textContent = outputREMOVEME;
			}
		});

	// Parse VCF
	} else if(fileType.endsWith(".vcf")) {
		const reader = new FileReader(); 

		// Function to read and parse VCF 
		reader.onload = function(event) {
			const vcfCards = (event.target.result).split("END:VCARD").filter(x => x.trim()); // Trim to remove any blanks

			const outputObj = vcfCards.map(vCard => vCardToObj(vCard));

			const outputREMOVEME = JSON.stringify(outputObj, null, 2);
	
			// REMOVE AND SEND TO SERVER INSTEAD
			document.getElementById("output").textContent = outputREMOVEME;
		}
		// Read file and call above function to parse into cards and then into JSON.
		reader.readAsText(file);
		

		

	// Something went wrong :(
	} else {
		alert("Error with file type");
	}


	function vCardToObj(vCard) {

		let parsedCard = {
			req_type: "create",
			user_id: null,
			first_name: null, 
			last_name: null,
			phone_number: null,
			email: null,
			img_url: null,
		};

		const splitData = vCard.split(/\r\n|\n/);
		let vCardLines = [];
		let temp = "";
		
		splitData.forEach(line => {
			if(line.startsWith(" ")) {
				temp += line.trim();
			} else {
				if(temp !== "") {
					vCardLines.push(temp);
				}
				temp = line.trim();
			}
		});
		if(temp !== "") {
			vCardLines.push(temp);
		}

		vCardLines.forEach(line => {

			// Get first and last name in correct order.
			if(line.startsWith("N:")) {
				var temp = line.substring(2).trim();
				temp = temp.split(";");
				if(temp[1] != "") { // Check to see if the first name exists
					parsedCard.first_name = temp[1];
				}
				if(temp[0] != "") { // Check for last name, otherwise it stays null.
					parsedCard.last_name = temp[0];
				}
			}

			// Get phone number if it exists.
			if(line.startsWith("TEL")) {
				parsedCard.phone_number = line.split(":")[1].trim();
			}

			// Get email if it exists
			if(line.startsWith("EMAIL")) {
				parsedCard.email = line.split(":")[1].trim();
			}

			// Get photo if it exists
			if(line.startsWith('PHOTO')) {
				parsedCard.img_url = line.substring(6).trim();
			}
		});

		return parsedCard;
	}
});

