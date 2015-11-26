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
				convertImgToDataURLviaCanvas( img.src, previewImage );
				img.download = entry.filename;
				//a.dispatchEvent(clickEvent);
			}, function(current, total) {
				unzipProgress.value = current;
				unzipProgress.max = total;
				li.appendChild(unzipProgress);
			});
		}
		
		function convertImgToDataURLviaCanvas( imgsrc, callback, outputFormat, compress ){
			var img = new Image();
			img.crossOrigin = 'Anonymous';
			img.onload = function(){
				var canvas = document.createElement('CANVAS');
				var ctx = canvas.getContext('2d');
				var dataURL;
				canvas.height = this.height;
				canvas.width = this.width;
				ctx.drawImage(this, 0, 0);
				if (compress=="undefined") compress = 0.85;
				if (outputFormat==undefined) outputFormat = "image/jpeg";
				dataURL = canvas.toDataURL(outputFormat,compress);
				callback(dataURL);
				canvas = null; 
			};
			img.src = imgsrc;
		}

		function previewImage(dataURL) {
			document.getElementById("_edetalle_PROYECTO_IMAGENBASE64").innerHTML = dataURL;
			document.getElementById("previewimg").src = dataURL;
			document.getElementById("previewimg").setAttribute("class","");
			
		}
		
		(function () {
			var imb64 = document.getElementById("_edetalle_PROYECTO_IMAGENBASE64").innerHTML;
			
			if (imb64!="") {
				previewImage(imb64);
				var file = document.getElementById("_edetalle_PROYECTO_ARCHIVO_LNK").innerHTML;
				loadPreviewShots( file.src );
			}
		})();
		
		function loadPreviewShots( _file ) {
			model.getEntries( _file, function(entries) {
				fileList.innerHTML = "";
				
				entries.forEach(function(entry,index,z) {
					
					var li = document.createElement("li");
					pselected = "";
					if (index==1) pselected = " selected";
										
					li.setAttribute("class","previewshot"+pselected);					
										
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
								//alert("convert to b64");
								convertImgToDataURLviaCanvas( event.target.src, previewImage );
							} );
							
							li.appendChild( a );
							li.appendChild( img );
							fileList.appendChild(li);
						}
					}
					
					
				});
			});
			
		}

		if (typeof requestFileSystem == "undefined")
			creationMethodInput.options.length = 1;
		fileInput.addEventListener('change', function() {
			
			fileInput.disabled = false; //just to avoid reloading a zip file while uncompressing actual one
			
			loadPreviewShots( fileInput.files[0] );
		}, false);
	})();

})(this);
