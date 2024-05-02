<template>
  <div>
    <div class="form-group">
      <label  class="col-md-4">
        {{label}} :
        <span class="text-danger" v-if="required==true" style="font-size: 24px; vertical-align: middle;">*</span>
      </label>
      <div class="col-md-7">
        <date-picker :disabled-date="dateAvailable" :value="input_data" value-type="format" format="YYYY-MM-DD" @input="dateChange"></date-picker>
      </div>
      <small class="text-danger" v-if="errors[error_attr]">{{errors[error_attr]}}</small>
    </div>
  </div>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
export default {
  props:['label','input_data','errors','error_attr','required','not_after'],
  components: {
    DatePicker
  },
  data(){
      return{
        input_data_selected:'',
      }
  },
  methods:{
      dateChange(event){
          this.input_data_selected = event;
          this.$emit('dateChanged', this.input_data_selected);
      },
      dateAvailable(date){
        if(this.$props.not_after){
          return date >= this.$props.not_after;
        }
        return false;
      }
  }
};
</script>