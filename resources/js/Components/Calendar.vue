<template>
    <Datepicker 
    v-model="selectedDate"
    :range="true" 
    :lang="lang"
    :showClearButton="true"
    :dateFormat="{  year: 'numeric', month: 'long', day: '2-digit' }"
    :disabled-start-date="disabledStartDate"
    :disabled-end-date="disabledEndDate"
    @change="handleDateChange"
    ></Datepicker>
</template>

<script>
  // special component - vue file

  import 'vue-datepicker-ui/lib/vuedatepickerui.css';
  import VueDatepickerUi from 'vue-datepicker-ui';

  export default {
    data () {
        return {
            selectedDate: [
                new Date(new Date().getTime() + 1 * 24 * 60 * 60 * 1000),
                new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
            ],
            lang: "en",
            disabledStartDate: {
                // to: new Date(new Date().getTime() + 1 * 24 * 60 * 60 * 1000),
                // from: new Date(new Date().getTime() + 1 * 24 * 60 * 60 * 1000)
            },
            disabledEndDate: {
                to: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                // from: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000)
            },
        }
    },
    components: {
      Datepicker: VueDatepickerUi
    },
    methods: {
        updateSelectedDate() {
            this.selectedDate.value = selectedDate
        },
        handleDateChange() {
            // Emit the selectedDate to the parent
            this.$emit('update-total', this.selectedDate);
            // Call the function passed from the parent
            this.test(this.selectedDate);
        },
    },

  }
</script>