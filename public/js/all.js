//formatadores
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

//primeira letra maiúscula

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}