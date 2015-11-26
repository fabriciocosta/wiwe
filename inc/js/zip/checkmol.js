(function(obj) {

	var requestFileSystem = obj.webkitRequestFileSystem || obj.mozRequestFileSystem || obj.requestFileSystem;
	obj.zip.workerScriptsPath = "/inc/js/zip/";
	
	function onerror(message) {
		alert(message);
	}

	function createTempFile(callback) {
		var tmpFilename = "tmp.dat";
		requestFileSystem(TEMPORARY, 4 * 1024 * 1024 * 1024, function(filesystem) {
			function create() {
				filesystem.root.getFile(tmpFilename, {
					create : true
				}, function(zipFile) {
					callback(zipFile);
				});
			}

			filesystem.root.getFile(tmpFilename, null, function(entry) {
				entry.remove(create, create);
			}, create);
		});
	}

	var model = (function() {
		var URL = obj.webkitURL || obj.mozURL || obj.URL;

		return {
			getEntries : function(file, onend) {
				zip.createReader(new zip.BlobReader(file), function(zipReader) {
					zipReader.getEntries(onend);
				}, onerror);
			},
			getEntryFile : function(entry, creationMethod, onend, onprogress) {
				var writer, zipFileEntry;

				function getData() {
					entry.getData(writer, function(blob) {
						var blobURL = creationMethod == "Blob" ? URL.createObjectURL(blob) : zipFileEntry.toURL();
						onend(blobURL);
					}, onprogress);
				}

				if (creationMethod == "Blob") {
					writer = new zip.BlobWriter();
					getData();
				} else {
					createTempFile(function(fileEntry) {
						zipFileEntry = fileEntry;
						writer = new zip.FileWriter(zipFileEntry);
						getData();
					});
				}
			}
		};
	})();

	(function() {
		var fileInput = document.getElementById("_fdetalle_PROYECTO_ARCHIVO");
		var unzipProgress = document.createElement("progress");
		var fileList = document.getElementById("file-list");
		var creationMethodInput = document.getElementById("creation-method-input");

		function download(entry, li, a) {
			model.getEntryFile(entry, creationMethodInput.value, function(blobURL) {
				var clickEvent = document.createEvent("MouseEvent");
				if (unzipProgress.parentNode)
					unzipProgress.parentNode.removeChild(unzipProgress);
				unzipProgress.value = 0;
				unzipProgress.max = 0;
				clickEvent.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
				a.href = blobURL;
				a.download = entry.filename;
				a.dispatchEvent(clickEvent);
			}, function(current, total) {
				unzipProgress.value = current;
				unzipProgress.max = total;
				li.appendChild(unzipProgress);
			});
		}
		
		function showimg(entry, li, img) {
			model.getEntryFile(entry, creationMethodInput.value, function(blobURL) {
				var clickEvent = document.createEvent("MouseEvent");
				if (unzipProgress.parentNode)
					unzipProgress.parentNode.removeChild(unzipProgress);
				unzipProgress.value = 0;
				unzipProgress.max = 0;
				clickEvent.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
				img.src = blobURL;
				img.download = entry.filename;
				//a.dispatchEvent(clickEvent);
			}, function(current, total) {
				unzipProgress.value = current;
				unzipProgress.max = total;
				li.appendChild(unzipProgress);
			});
		}
		
		function uploadBlob( IMG ) {
			// create a blob here for testing
			var blob = new Blob(["i am a blob"]);
			//var blob = yourAudioBlobCapturedFromWebAudioAPI;// for example   
			var reader = new FileReader();
			// this function is triggered once a call to readAsDataURL returns
			reader.onload = function(event){
				var fd = new FormData();
				fd.append('fname', 'test.txt');
				fd.append('data', event.target.result);
				$.ajax({
					type: 'POST',
					url: 'upload.php',
					data: fd,
					processData: false,
					contentType: false
				}).done(function(data) {
					// print the output from the upload.php script
					console.log(data);
				});
			};      
			// trigger the read from the reader...
			reader.readAsDataURL(blob);

		}

		if (typeof requestFileSystem == "undefined")
			creationMethodInput.options.length = 1;
		fileInput.addEventListener('change', function() {
			
			fileInput.disabled = false; //just to avoid reloading a zip file while uncompressing actual one
			
			model.getEntries(fileInput.files[0], function(entries) {
				fileList.innerHTML = "";
				entries.forEach(function(entry) {
					var li = document.createElement("li");
					li.setAttribute("class","previewshot");
					var a = document.createElement("a");
					a.textContent = entry.filename;
					
					var img = document.createElement("img");
					showimg( entry, li, img );
					
					//only show jpgs
					if ( entry.filename.indexOf("previewshots")>0 || entry.filename.indexOf("preview_shot")>0 ) {
						if ( entry.filename.indexOf(".jpg")>0 ) {
							img.setAttribute("class","previewshot");
							a.href = "#";
							a.addEventListener("click", function(event) {
								if (!a.download) {
									download(entry, li, a);
									event.preventDefault();
									return false;
								}
							}, false);
							
							img.addEventListener( "click", function(event) {
								alert("uploadblob");
								UploadBlob( event.target );
							} );
							
							li.appendChild( a );
							li.appendChild( img );
							fileList.appendChild(li);
						}
					}
					
					
				});
			});
		}, false);
	})();

})(this);
