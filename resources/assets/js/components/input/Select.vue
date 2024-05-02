<template>
  <div>
    <div class="form-group">
      <label :class="is_break_label == true ? 'col-md-12' : 'col-md-4'">
        <span :style="label_style">{{label}}:</span>
        <span class="text-danger" v-if="required == true" style="font-size: 24px; vertical-align: middle;">*</span>
      </label> 
      <div :class="is_break_label == true ? 'col-md-12' : 'col-md-7'">
        <select id="gender" class="form-control input-sm"  @change="changeSelect" required>
          <option selected value="">--SELECT--</option>
          <option :value="option[value_attr]" v-for="(option , index) in options" :key="index" :selected="old_value == option[old_value_attr]">{{option[name_attr]}} <span v-if="name_attr2"> - {{option[name_attr2]}}</span></option>
        </select>
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
    props:['is_break_label','options','label','errors','error_attr','value_attr','name_attr','name_attr2','required','label_style','old_value',"old_value_attr"],
    data(){
        return{
            selected_value:'',
        }
    },
    computed:{
       
    },
    methods:{
        changeSelect(event){
           this.selected_value = event.target.value;
           this.$emit('changedSelect', this.selected_value);
        }
    }

}
</script>