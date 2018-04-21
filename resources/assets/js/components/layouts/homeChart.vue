<script>
import {
  HorizontalBar
} from 'vue-chartjs';

export default {
  props: ['data'],
  extends: HorizontalBar,
  mounted() {
    //let Months = new Array();
    let Labels = new Array();
    let Percs = new Array();
    let Colors = new Array();
    //          this.axios.get(uri).then((response) => {
    //             let data = response.data;

    if (this.data) {
      this.data.forEach(element => {
        //Months[prop].push(moment(String(this.data[prop].timestamp)).format('YYYY-MM'));
        Labels.push(element.stockName);
        Percs.push(element.perc);
        Colors.push(element.color);
      });
      console.log(this.data);



      //      });

      //     if (this.symbols) {
      //       this.data.forEach(element => {
      //         Months.push(moment(String(element.timestamp)).format('YYYY-MM'));
      //         Labels.push(element.stockName);
      //         Prices.push(element.close);
      //         Colors.push(element.color);
      //       });
      this.renderChart({
        labels: Labels,
        //multiple datasets
        datasets: [{
            backgroundColor: Colors,
            data: Percs,
          }

        ]
      }, {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        }
      })
      console.log(Labels, Percs, Colors)
    } else {
      console.log('No data');
    }
  },
}
</script>
