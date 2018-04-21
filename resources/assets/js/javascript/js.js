racaz = window.racaz || {};

racaz = function() {

  //   var yourVar1;
  //   var yourVar2;
  //formatadores
  var slug = window.location.pathname.slice(1);
  var locale = 'pt-BR';
  //para valores monetarios
  var options = {
    style: 'currency',
    currency: 'brl',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  };
  var currFormatter = new Intl.NumberFormat(locale, options);
  //para percentagens
  var percentageOptions = {
    style: 'percent',
    minimumFractionDigits: 3,
    maximumFractionDigits: 3
  }
  var percFormatter = new Intl.NumberFormat(locale, percentageOptions);

  var columnDesc = [
    ["id", 'ID'],
    ["name", 'Nome'],
    ["email", 'E-mail'],
    ["role_id", 'Permissão'],
    ["created_at", "Criado em"],
    ["updated_at", "Atual. em"],
    ["timestamp", "Data do reg."],
    ["symbol", "Ticket"],
    ["stock_id", "Ticket"],
    ["type", "Tipo"],
    ["cnpj", "CNPJ"],
    ["open", "Abertura"],
    ["volume", "Volume"],
    ["price", "Preço"],
    ["low", "Baixa"],
    ["high", "Alta"],
    ["close", "Fecham"],
    ["date_invest", "Data inv."],
    ["broker_fee", "Corretagem"],
    ["broker_id", "Corretora"],
    ["broker", "Corretora"],
    ["quote", "Cotação"],
    ["quant", "Quant."],
    ["user_id", "Usuario"],
    ["total", "Total"],
    ["percentage", "%"],
    ["invests", "Investimentos"],
    ["stocks", "Ações"],
    ["brokers", "Corretoras"],
    ["users", "Usuários"],
    ["monthlyquotes", "Cotações mensais"]
  ]


  capitalizeFirstLetter = function(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  };

  columnName = function(column) {
    let iterable = new Map(columnDesc);
    for (let [key, value] of iterable) {
      if (key == column) {
        return value;
      }
    }
  };
  fieldsFiller = function(data) {
    var fields = [];
    //depois ele vai fazer o tratamento dos dados apresentados, trazendo para formatos de apresentacao
    if (Array.isArray(data)) {
      for (const value of data) {
        //aqui ele formata as datas
        if (value == "timestamp" || value == "date_invest") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return moment(String(value)).format('DD/MM/YYYY hh:mm')
            }
          });
          //aqui formata os precos
        } else if (value == "open" || value == "high" || value == "low" || value == "close" || value == "price" || value == "quote" || value == "broker_fee" || value == "total") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return currFormatter.format(value);
              //return value;
            }
          });
          //aqui traz volume para valor inteiro, sem fracao
        } else if (value == "volume" || value == "quant") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return parseFloat(value).toFixed(0);
            }
          });
          //o item _cellVariants nao é renderizado
        } else if (value == "percentage") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return percFormatter.format(value);
            }
          });
          //o item _cellVariants nao é renderizado
        } else if (value == "_cellVariants" || value == "created_at" || value == "updated_at") {
          //
        } else {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
          });
        }
      }
      return fields;
    } else {
      console.log(data);
    }
  };
  formtt = function(data) {
    //depois ele vai fazer o tratamento dos dados apresentados, trazendo para formatos de apresentacao
    if (Array.isArray(data)) {
      //aqui ele formata as datas
      if (data[0] == "timestamp" || data[0] == "date_invest") {
        data[1] = moment(String(data[1])).format('DD/MM/YYYY hh:mm');
        //aqui formata os precos
      } else if (data[0] == "open" || data[0] == "high" || data[0] == "low" || data[0] == "close" || data[0] == "price" || data[0] == "quote" || data[0] == "broker_fee" || data[0] == "total") {
        data[1] = currFormatter.format(data[1]);
        //aqui traz volume para valor inteiro, sem fracao
      } else if (data[0] == "volume" || data[0] == "quant") {
        data[1] = parseFloat(data[1]).toFixed(0);
        //o item _cellVariants nao é renderizado
      } else if (data[0] == "percentage") {
        data[1] = percFormatter.format(data[1]);
        //o item _cellVariants nao é renderizado
      } else if (data[0] == "created_at" || data[0] == "updated_at") {
        data[1] = moment(String(data[1])).format('DD/MM/YYYY hh:mm');
      } else {
        console.log(data);
      }
      return data[1];
    } else {
      console.log(data);
    }
  }
  return {
    "capitalizeFirstLetter": capitalizeFirstLetter,
    "slug": slug,
    "columnName": columnName,
    "fieldsFiller": fieldsFiller,
    "formtt": formtt
  }

}();
