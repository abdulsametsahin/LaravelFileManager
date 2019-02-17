@include('UploadManager::partials.header')

<div id="uploadManager">
	<!-- Header -->
	<div id="header">
		<div class="path">
			<i class="fa fa-arrow-left back" @click="back()"></i>
			<i class="fa fa-folder-open-o"></i>
			<span>@{{ path }}</span>

			<div class="clear"></div>
		</div>
		<div class="menu">
		</div>
		<div class="clear"></div>
	</div>
	<!-- Header end-->
	<!-- Content -->
	<div id="content">
		<!-- Loading -->
		<div class="loading" v-if="isLoading">
			<i class="fa fa-circle-o-notch fa-spin"></i>
		</div>
		<!-- Loading end -->
		<div id="menu">
			<div class="block" v-if="selectedFiles().length > 0">
				<input type="text" v-model="movePath" placeholder="Moving path">
				<button @click="moveFiles()">
					<i class="fa fa-arrows"></i>
					Move
				</button>
			</div>
			<div class="block" @click="deleteFiles()" v-if="selectedFiles().length > 0">
				<button>
					<i class="fa fa-times"></i>
					Delete
				</button>
			</div>

			<div class="block">
				<input type="text" v-model="newFolderName" v-on:keyup.enter="createFolder()" placeholder="Folder name">
				<button @click="createFolder()">
					<i class="fa fa-plus"></i>
					Create Folder
				</button>
			</div>
			
			<div class="clear"></div>
		</div>
		<!-- Folders -->
		<div class="folders" v-if="!isLoading && folders.length > 0" >
			<!-- Go  back -->
			<table cellspacing="0">
				<tr>
					<th></th>
					<th>Name</th>
					<th>Size</th>
					<th>URL</th>
				</tr>
				<tr v-for="f in folders" @click="f.type != 'folder' ? selectedFile = f : null">
				 	<td @click="f.isSelected ? f.isSelected = false : f.isSelected = true" style="width: 30px; text-align: center;">
				 		<span :class="'select '+ (f.isSelected ? 'checked' : '')" style="text-align: center;">
							<i class="fa fa-circle fa-lg" v-if="!f.isSelected"></i>
							<i class="fa fa-check-circle fa-lg" v-if="f.isSelected"></i>
						</span>
				 	</td>
				 	<td @dblclick="counter += 1, f.type == 'folder' ? getDir(f.path) : '';">
				 		<div class="name">
				 			<i class="fa fa-folder" v-if="f.type == 'folder'"></i>
							<i class="fa fa-file-text-o" v-if="f.type == 'file'"></i>
							<i class="fa fa-picture-o" v-if="f.type == 'image'"></i>
							@{{ f.name }}
				 		</div>
				 	</td>
				 	<td>
				 		<span v-if="f.type == 'folder'">--</span>
				 		<span v-if="f.type != 'folder'">@{{ f.size }} MB</span>
				 	</td>
				 	<td class="external">
				 		<span v-if="f.type == 'folder'">&nbsp;</span>
				 		<span v-if="f.type != 'folder'">
							<a :href="'{{ url('/') }}/'+f.path" target="_blank"> <i class="fa fa-external-link fa-lg"></i> </a>
				 		</span>
				 	</td>
				 </tr>
				
			</table>
		</div>
		<!-- Folders end -->
		<!-- Empty -->
		<div class="emptyFolder" v-if="!isLoading && folders.length == 0">
			Empty folder.
		</div>
		<!-- Empty end-->
	</div>
	<!-- Content end -->
	<!-- Upload Box-->
	@include('UploadManager::upload')
	<!-- Upload Box end -->
	<!-- File info -->
	<div id="fileInfo" v-if="selectedFile">
		<img :src="'{{ url('/') }}/'+selectedFile.path" v-if="selectedFile.type == 'image'" width="100%">
		<a :href="'{{ url('/') }}/'+selectedFile.path">@{{ selectedFile.name }}</a>
	</div>
	<!-- File info -->
</div>

@include('UploadManager::partials.footer')