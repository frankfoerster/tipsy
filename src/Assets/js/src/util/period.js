const period = (startDate, endDate, format, join = ' - ') => {
  format = format || 'DD.MM.YYYY';

  let parts = [];

  startDate && parts.push(startDate.format(format));
  endDate && parts.push(endDate.format(format));

  return parts.join(join);
};

export default period;
