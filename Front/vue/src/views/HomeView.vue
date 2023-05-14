<template>
  <!-- <header class="w-100 bg-dark">
    <div class="container py-2 text-white">
      <p></p>
      <p></p>
      <p></p>
      <p></p>
    </div>
  </header> -->
  <main class="w-100 h-100">
    
    <table class="table w-50 h-50 m-3" id="full-screen-data">
      <template v-for="places in data_places" :key="data_places.indexOf(places)" >
        <tr >
          <template v-for="place in places" :key="places.indexOf(place)">
            <td class="col border border-1 border-dark">
              <input 
                type="text" 
                v-model="data_places[data_places.indexOf(places)][places.indexOf(place)].name"
                draggable="true"
                @dragstart="ChangePlaceDrag($event, place)"
                @drop="ChangePlaceDrop($event, place)"
                @dragenter.prevent
                @dragover.prevent
              >
            </td>
          </template>
        </tr>
        
      </template>
      <tr class="hiding-button-in-fullscreen-mode" v-if="fullScreenButton">
          <td colspan="8" class="col border border-1 border-dark">
            <button class="btn btn-warning bg-warning text-white fs-3" @click="fullScreenOff">Exit</button>
          </td>
      </tr>
    </table>
    <div class="w-100 position-absolute bottom-0 left-0 p-3 d-flex align-items-center justify-content-between">
      <button class="btn btn-primary fs-3" @click="dowload_file">Скачать фаил</button>
      <button class="btn btn-primary fs-3" @click="copyToClipboard">Скопировать в буфер</button>
      <button class="btn btn-primary fs-3" @click="fullScreenOn">Режим прзентации</button>
      <div class="border border-2 p-3 border-dark">
        <div class="mb-3">
          <label for="formFile" class="form-label">Нажмите чтобы выбрать или перетащите фаил</label>
          <input 
            class="form-control" 
            type="file" 
            accept=".txt" 
            id="formFile"
            @change="handleFileChange"  
          >
        </div>
        <button class="btn btn-primary disabled" v-if="!file">Upload</button>
        <button class="btn btn-primary" v-if="file" @click="upload_file">Upload</button>
      </div>
      
    </div>
  </main>
</template>

<script>

export default {
  name: 'HomeView',
  data () {
    return {
      file:null,
      file_text:'',
      fullScreenButton:false,
      data_places:[
        [
          {
            id:1,
            name:''
          },{
            id:2,
            name:''
          },{
            id:3,
            name:''
          },{
            id:4,
            name:''
          },{
            id:5,
            name:''
          },{
            id:6,
            name:''
          },{
            id:7,
            name:''
          },{
            id:8,
            name:''
          }
        ],
        [
          {
            id:9,
            name:''
          },{
            id:10,
            name:''
          },{
            id:11,
            name:''
          },{
            id:12,
            name:''
          },{
            id:13,
            name:''
          },{
            id:14,
            name:''
          },{
            id:15,
            name:''
          },{
            id:16,
            name:''
          }
        ],
        [
          {
            id:17,
            name:''
          },{
            id:18,
            name:''
          },{
            id:19,
            name:''
          },{
            id:20,
            name:''
          },{
            id:21,
            name:''
          },{
            id:22,
            name:''
          },{
            id:23,
            name:''
          },{
            id:24,
            name:''
          }
        ],
        [
          {
            id:25,
            name:''
          },{
            id:26,
            name:''
          },{
            id:27,
            name:''
          },{
            id:28,
            name:''
          },{
            id:29,
            name:''
          },{
            id:30,
            name:''
          },{
            id:31,
            name:''
          },{
            id:32,
            name:''
          }
        ]
      ],
      data_drag:null,
      data_drop:null,
      data_drag_obj:null,
      data_drag_position:null,
    }
  },
  created () {
    this.read_from_localstorage();
    this.parse_text_data();

    document.addEventListener('fullscreenchange', e => {
      if (document.fullscreenElement) {
        this.fullScreenButton = true
      } else {
        this.fullScreenButton = false
      }
    })
  },
  methods: {
    find_data (data_places, place_id){
      let place_position = null
      let place_obj = null
      data_places.some((array, i) => {
        const index = array.findIndex((obj) => obj.id === place_id)
        if (index !== -1) {
          console.log('array', array)
          console.log('index', index)
          place_obj = array[index]
          place_position = {row:i, column: index}
          return true
        }

        return false;
      })
      return [place_obj, place_position]
    },
    ChangePlaceDrag (event, place){
      console.log(place, this.data_places)
      let [place_obj, place_position] = this.find_data(this.data_places, place.id)
      
      event.dataTransfer.dropEffect = 'move'
      event.dataTransfer.effectAllowed = 'move'

      this.data_drag_obj = place_obj
      this.data_drag_position = place_position
    },
    ChangePlaceDrop (event, place){
      
      const [place_obj_drop, place_position_drop] = this.find_data(this.data_places, place.id)
      console.log(place_obj_drop)

      this.data_places[place_position_drop.row][place_position_drop.column] = this.data_drag_obj
      this.data_places[this.data_drag_position.row][this.data_drag_position.column] = place_obj_drop
      
      // this.write_changes_in_indexDB()
    },
    fullScreenOn () {
      const element = document.getElementById('full-screen-data');
      element.requestFullscreen();
    },
    fullScreenOff () {
      document.webkitExitFullscreen();
    },
    handleFileChange(event) {
      const file = event.target.files[0];
      // Выполните необходимую обработку выбранного файла
      this.file = file;
    },
    copy_text_data_from_reactiv_data() {
      let content = "# VIP List\n\n"

      for (let ii of this.data_places) {
        for (let i of ii) {
          content+=`- ${i.name}\n`
        }
        content+='\n\n'
      }

      return content
    },
    dowload_file () {
      let content = this.copy_text_data_from_reactiv_data()

      const filename = 'vip-list.txt';

      const element = document.createElement('a');
      element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
      element.setAttribute('download', filename);

      element.style.display = 'none';
      document.body.appendChild(element);

      element.click();

      document.body.removeChild(element);
    
    },
    copyToClipboard() {
      let content = this.copy_text_data_from_reactiv_data()

      navigator.clipboard.writeText(content)
        .then(() => {
          console.log('Text copied to clipboard');
        })
        .catch((error) => {
          console.error('Unable to copy text:', error);
        });
    },
    upload_file() {
      const self = this;
      var reader = new FileReader();
      reader.onload = function(event) {
        let fileContent = event.target.result;
        self.file_text = fileContent
        self.parse_text_data()
      };
      reader.readAsText(this.file);
    },
    parse_text_data() {
      console.log(this.file_text)
      this.file_text = this.file_text.split('\n').slice(2)
      this.fill_table_data_text()
    },
    fill_table_data_text() {
      let row = 0
      let column = 0
      for (let i of this.file_text) {
        if (i.indexOf('- ') >= 0) {
          if (column == 8) {
            column = 0
            row += 1
          }
          this.data_places[row][column].name = i.slice(2)
          column += 1
        }
      }
      
    },
    write_in_localstorage() {
      let text = this.copy_text_data_from_reactiv_data()
      localStorage.setItem('data', text)
    },
    read_from_localstorage() {
      this.file_text = localStorage.getItem('data') 
      return localStorage.getItem('data')
    }
  },
  watch: {
    data_places: {
      handler: function(newVal, oldVal) {
        this.write_in_localstorage()
      },
      deep: true // Глубокое наблюдение за изменениями вложенных свойств
    }
  }
  
}
</script>
<style scoped>
  input:focus {outline: 0;}
  .col {
    text-align: center;
    padding: 10px 0;
    background-color: #fff;
  }
  .col input {
    border: 0;
    font-size: 20px;
    text-align: center;
    background-color: #fff;
    width: 90%;
  }

  .col input:-webkit-full-screen {
    font-size: 30px;
  }

</style>