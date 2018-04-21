<template>
<div>
  <b-card-group deck>
    <!-- primeiro  card -->
    <b-card header="Portfolio por investimento" header-tag="header">
      <!--                  <homechartpizza :data="this.invests" :width="400" :height="200"></homechartpizza> -->
      <homechartpizza :data="this.invests"></homechartpizza>
      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" href="#" variant="primary">Vai chart</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
    <!-- segundo  card -->
    <b-card header="Histórico de ações na carteira" header-tag="header" title="Mensal">
      <b-input-group>
        <b-form-select v-model="selected" v-on:input="goChart">
          <option slot="first" v-model="selected" v-bind:value="null">-- none --</option>
          <option v-for="stock in stocks" v-bind:value="stock.value">{{ stock.text }}</option>
        </b-form-select>
      </b-input-group>
      <br>
      <br>
      <homechartint v-if="this.selected !== null" :chart-data="datacollection" :width="400" :height="200"></homechartint>
      <!--              <homechart :data="this.monthlyquotes"></homechart> -->
      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" href="#" variant="primary">Vai chart</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </b-card-group>
  <br>
  <b-card-group deck>
    <!-- terceiro  card -->
    <b-card header="Portfolio por tipo de investimento" header-tag="header">
      <!--                  <homechartpizza :data="this.invests" :width="400" :height="200"></homechartpizza> -->
      <homechartpizza :data="this.results"></homechartpizza>
      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" href="#" variant="primary">Vai chart</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
    <!-- quarto  card -->
    <b-card header="Resultado por tipo de investimento" header-tag="header">
      <!--              <homechart :data="this.monthlyquotes" :width="400" :height="200"></homechart> -->
      <homechart :data="this.chart2"></homechart>
      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" href="#" variant="primary">Vai chart</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </b-card-group>
  <br>
  <b-card-group deck>
    <!-- terceiro  card -->
    <b-card header="vue chartkick line" header-tag="header">
      <pie-chart :donut="false" prefix="$" thousands="." decimal="," :data="this.pie" :messages="{empty: 'Sem dados'}"></pie-chart>
      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" href="#" variant="primary">Vai chart</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
    <!-- quarto  card -->
    <b-card header="Performance das ações da carteira" header-tag="header">
      <line-chart :download="true" legend="bottom" :messages="{empty: 'Sem dados'}" prefix="R$ " decimal="," :curve="false" xtitle="Tempo" ytitle="Reais" :data="this.forcharts"></line-chart>
      <div slot="footer">
        <b-row align-h="end">
          <b-col cols="3">
            <b-button align-self="end" href="#" variant="primary">Vai chart</b-button>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </b-card-group>
</div>
</template>
<script>
export default {
  props: ['invests', 'monthlyquotes', 'results', 'forcharts', 'pie'],
  data() {
    return {
      stocks: [],
      selected: null,
      chartData: [],
      datacollection: [],
      Months: [],
      Labels: [],
      Prices: [],
      datasets: [],
      Months: [],
      homeChart: [],
      Labels: [],
      Prices: [],
      Colors: [],
      chart2: [],
    }
  },
  created: function() {
    //cria lista de stocks com seus ids para passar para chart especifico
    //sortOptions() {
    //this.stocks.push([...new Set(this.invests.map(item => ["text: "+ item.symbol,"value: " + item.stock_id].join(",")))]);
    // this.stocks.push([...new Set(this.invests.map(item => item.symbol))]);
    //      this.stocks.push(this.invests.map(item => item.symbol),this.invests.map(item => item.stock_id));
    //console.log(this.stocks);
    let uniqueSymbol = [...new Set(this.invests.map(item => item.symbol))];
    //console.log(uniqueSymbol);
    let uniqueStock_id = [...new Set(this.invests.map(item => item.stock_id))];
    //console.log(uniqueStock_id);
    for (var i = 0; i < uniqueSymbol.length; i++) {
      this.stocks.push({
        text: uniqueSymbol[i],
        value: uniqueStock_id[i]
      })
    }

    this.Months = [...new Set(this.monthlyquotes.map(element => moment(String(element.timestamp)).format('YYYY-MM')))];


    let uniqueStockName = [...new Set(this.results.map(item => item.stockName))];
    //console.log(uniqueSymbol);
    let uniquePercentage2 = [...new Set(this.results.map(item => item.percentage))];
    let uniqueColor2 = [...new Set(this.results.map(item => item.color))];
    for (var i = 0; i < uniqueStockName.length; i++) {
      this.chart2.push({
        stockName: uniqueStockName[i],
        perc: uniquePercentage2[i],
        color: uniqueColor2[i],
      })
    }

//  this.datasets = this.monthlyquotes.reduce(function(cont, item) {
//       cont[item.stockName] = cont[item.stockName] || [];
//       cont[item.stockName].push(item);
//       //cont[item.stockName].data = { item.timestamp : item.close };
//       return cont;
//     }, Object.create(null));


    //return this.stocks;
  },
  methods: {
    goChart() {
      console.log(this.selected);
      //this.chartData = [];
      //this.datacollection = [];
      this.Months = [];
      this.Labels = [];
      this.Prices = [];
      axios.get('/api/getintchart', {
          params: {
            query: this.selected
          }
        })
        .then(response => response.data)
        .then(data => {
          Vue.set(this, 'chartData', data)
        })
        .then(() => {
          if (this.chartData) {
            this.chartData.forEach(element => {
              this.Months.push(moment(String(element.timestamp)).format('YYYY-MM'));
              this.Prices.push(element.close);
            });
            this.Labels = [...new Set(this.chartData.map(item => item.stock_id))];

            this.datacollection = {
              labels: this.Months,
              datasets: [{
                label: this.Labels,
                backgroundColor: '#FC2525',
                data: this.Prices
              }]
            };
            //set(this , 'datacollection', temp )

          } else {
            console.log("erro")
          }
        })
        .catch(error => {
          console.log(error)
        })
      //.then(response => {
      //this.chartData = response.data;


    },
    testing() {}
  }
}
</script>
