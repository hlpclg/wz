/*
 * 最后修正于2014年8月2日00:09:53  by：sunshine
		[Leo.C, Studio] (C)2004 - 2008
		
   		$Hanization: LeoChung $
   		$E-Mail: who@imll.net $
   		$HomePage: http://imll.net $
   		$Date: 2008/11/8 18:02 $
*/
/*
	A simple class for displaying file information and progress
	Note: This is a demonstration only and not part of SWFUpload.
	Note: Some have had problems adapting this class in IE7. It may not be suitable for your application.
*/

// Constructor
// file is a SWFUpload file object
// targetID is the HTML element id attribute that the FileProgress HTML structure will be added to.
// Instantiating a new FileProgress object with an existing file will reuse/update the existing DOM elements
function FileProgress(file, targetID) {
	this.fileProgressID = file.id;

	this.opacity = 100;
	this.height = 0;

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progress";


		var progressText = document.createElement("span");
		progressText.className = "progressName";
		progressText.appendChild(document.createTextNode(file.name));

		var progressStatus = document.createElement("span");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));


		var progressHide1 = document.createElement("div");
		progressHide1.className = "progress1";

		var progressHide2 = document.createElement("div");
		progressHide2.className = "progress2";

		var progressHide3 = document.createElement("div");
		progressHide3.className = "progress3";

		var progressBar = document.createElement("div");
		progressBar.className = "progress-bar progress-bar-success progress-bar-striped active";

	

		this.fileProgressElement.appendChild(progressHide1);
		this.fileProgressElement.appendChild(progressHide2);
		this.fileProgressElement.appendChild(progressHide3);
		this.fileProgressElement.appendChild(progressBar);
		this.fileProgressWrapper.appendChild(this.fileProgressElement);
		this.fileProgressWrapper.appendChild(progressText);
		this.fileProgressWrapper.appendChild(progressStatus);
		this.fileProgressWrapper.appendChild(progressCancel);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progress";
	this.fileProgressElement.childNodes[3].className = "progress-bar progress-bar-info progress-bar-striped";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progress";
	this.fileProgressElement.childNodes[3].className = "progress-bar progress-bar-success progress-bar-striped";
	this.fileProgressElement.childNodes[3].style.width = "100%";

	var oSelf = this;
	setTimeout(function () {
		// oSelf.disappear();
		$("#file_upload_modal").modal('hide');
	}, 1000);
};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progress";
	this.fileProgressElement.childNodes[3].className = "progress-bar progress-bar-danger progress-bar-striped";
	this.fileProgressElement.childNodes[3].style.width = "100%";

	var oSelf = this;
	setTimeout(function () {
		// oSelf.disappear();
		$("#file_upload_modal").modal('hide');
		swfu.cancelUpload();
	}, 1000);
};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progress";
	this.fileProgressElement.childNodes[3].className = "progress-bar progress-bar-warning progress-bar-striped";
	this.fileProgressElement.childNodes[3].style.width = "100%";

	var oSelf = this;
	setTimeout(function () {
		oSelf.disappear();
	}, 2000);
};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressWrapper.childNodes[2].innerHTML = status;
};

// Show/Hide the cancel button
FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
	this.fileProgressWrapper.childNodes[3].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressWrapper.childNodes[3].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};

// Fades out and clips away the FileProgress box.
FileProgress.prototype.disappear = function () {

	var reduceOpacityBy = 15;
	var reduceHeightBy = 4;
	var rate = 30;	// 15 fps

	if (this.opacity > 0) {
		this.opacity -= reduceOpacityBy;
		if (this.opacity < 0) {
			this.opacity = 0;
		}

		if (this.fileProgressWrapper.filters) {
			try {
				this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = this.opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.opacity + ")";
			}
		} else {
			this.fileProgressWrapper.style.opacity = this.opacity / 100;
		}
	}

	if (this.height > 0) {
		this.height -= reduceHeightBy;
		if (this.height < 0) {
			this.height = 0;
		}

		this.fileProgressWrapper.style.height = this.height + "px";
	}

	if (this.height > 0 || this.opacity > 0) {
		var oSelf = this;
		setTimeout(function () {
			oSelf.disappear();
		}, rate);
	} else {
		this.fileProgressWrapper.style.display = "none";
	}
};