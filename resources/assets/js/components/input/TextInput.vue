<template>
  <div>
    <div class="form-group">
      <label class="col-md-4">
        <span :style="label_style">{{label}}:</span>
        <span class="text-danger" v-if="required==true" style="font-size: 24px; vertical-align: middle;">*</span>
      </label>
      <div class="col-md-7">
        <small class="text-info" v-if="show_text_limit == true"><strong>Max Characters is 200</strong> </small>
        <input
          type="text"
          :value="input_data"
          class="form-control input-sm"
          required
          :placeholder="placeholder"
          @input="changeInputData"
          :disabled="disabled"
          
        />
        <small
          class="text-danger"
          v-if="errors[error_attr]"
        >{{errors[error_attr]}}</small>
      </div>
    </div>
  </div>
</template>
<script>
export default {
    props:['show_text_limit','text_left','input_data','label','errors','error_attr','placeholder','required','disabled','label_style'],
    data(){
        return{
          input_data_updated : ''
        }
    },
    methods:{
        changeInputData(event){
            this.input_data_updated = event.target.value;
            this.$emit('inputDataChanged', this.input_data_updated);
        }
    },
    computed:{
      // otherReligionCharactersLeft(){
      //     var char = this.input_data_updated.length,
      //     limit = 200;
      //     return (limit - char) + " / " + limit + " characters left";
      // },
    }
}
</script>