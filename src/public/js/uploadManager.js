var um_url = $('meta[name="um_url"]').attr('content')
var token = $('meta[name="csrf-token"]').attr('content');

let uploadManager = new Vue({
	'name': 'UploadManager',
	'el': '#uploadManager',
	data: {
		counter: 0,
		display: 'folders',
		folders: [],
		isLoading: false,
		path: '/',
		selectedFile: null,
		newFolderName: null,
		movePath: null,
	},
	mounted () {
		this.getDir();
		var thiz = this;
	},
	methods: {
		moveFiles () {
			$.post('/upload-manager/move', {
				movePath: uploadManager.movePath, 
				selectedFiles: (uploadManager.selectedFiles())
			}, function(data, textStatus, xhr) {
				if (data.status == "success")
					uploadManager.reload();
				else
					alert(data.message);
			});
		},
		deleteFiles () {
			$.post('/upload-manager/delete', {selectedFiles: (uploadManager.selectedFiles())}, function(data, textStatus, xhr) {
				if (data.status == "success")
					uploadManager.reload();
				else
					alert(data.message);
			});
		},
		selectedFiles () {
			var files = [];
			$.each(this.folders, (i, f) => {
				if (f.isSelected) {
					files.push(f.path);
				}
			})
			return files;
		},
		createFolder () {
			$.post('/upload-manager/create-folder', {folderName: uploadManager.newFolderName}, function(data, textStatus, xhr) {
				if (data.status == "success")
				{
					uploadManager.newFolderName = null;
					uploadManager.reload();
				}
				else
					alert(data.message);
			});
		},
		reload () {
			this.getDir(this.path);
		},
		getDir (path) {
			function trim(s, mask) {
			    while (~mask.indexOf(s[0])) {
			        s = s.slice(1);
			    }
			    while (~mask.indexOf(s[s.length - 1])) {
			        s = s.slice(0, -1);
			    }
			    return s;
			}

			path = path || '/';
			var thiz = this;
			this.isLoading = true;
			this.folders = [];
			if (path != '/')
				this.path = trim(path, "/");
			else
				this.path = "/";

			$.getJSON('/upload-manager/get-dir', {path: path}, function(json, textStatus) {
				thiz.folders = json;
				thiz.isLoading = false;
			});
		},
		back () {
			if (this.path != "/") {
				let path = this.path.split('/');
				let newPath = "";
				$.each(path, (i, p) => {
					if (p != path[path.length-1] && p.length)
						newPath += p + "/";
				})
				
				return this.getDir(newPath);
			}
			return true;
		}
	}
});


/*
 * Func. for hiding upload box.
 */
function hideUploadBox() {
	$("#uploader").hide();
	$("#uploadNew").show();
}

/*
 * Upload file(s) button click event
 */
$("#uploadNew").click(function(event) {
	showUploadBox();
});

/*
 * Uploadbox close button event
 */
$("#uploader .close").click(function(event) {
	hideUploadBox();
});

/*
 * Show upload box and 
 * create new filepond object
 */
function showUploadBox() {
	$("#uploader").show();
	$("#uploadNew").hide();
	FilePond.registerPlugin(
	  FilePondPluginImagePreview,
	  FilePondPluginImageCrop,
	);

	FilePond.setOptions({
	    server: {
	    	url: um_url,
	    	process: {
	            url: '/upload',
	            method: 'POST',
	            withCredentials: false,
	            headers: {
	            	'X-CSRF-TOKEN': token
	            },
	            timeout: 7000,
	            onload: null,
	            onerror: null,
	            ondata: null,
	        }
	    }
	});



	var pond = FilePond.create(
	    document.querySelector('#uploadinput')
	);
	pond.on('processfile', (error, file) => {
	    if (error) {
	        console.log('Oh no');
	        return;
	    }else {
	    	pond.removeFile(file.id);
	    	setTimeout(() => {
	    		//hideUploadBox();
	    		uploadManager.reload();
	    	},50);
	    }
	});
}