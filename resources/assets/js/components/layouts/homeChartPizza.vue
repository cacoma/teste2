<script>
import {
  Pie
} from 'vue-chartjs';

export default {
  props: ['data'],
  extends: Pie,
  mounted() {
    //let Months = new Array();
    let Labels = new Array();
    let Totals = new Array();
    let Colors = new Array();
    //          this.axios.get(uri).then((response) => {
    //             let data = response.data;
    if (this.data) {
      this.data.forEach(element => {
        //Months.push(moment(String(element.timestamp)).format('YYYY-MM'));
        Labels.push(element.stockName);
        Totals.push(element.total);
        Colors.push(element.color);
      });
      this.renderChart({
        datasets: [{
          //label: 'ITSA3.SA',
          backgroundColor: Colors,
          data: Totals,
        }],
        labels: Labels,
      }, {
        responsive: true,
        maintainAspectRatio: false
      })
    } else {
      console.log('No data');
    }
  },
}
</script>
