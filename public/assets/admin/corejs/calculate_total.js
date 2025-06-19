function updateSummary() {
  let totalVolume = 0;
  let totalWeight = 0;
  let totalSubtotal = 0;

  packages.forEach(pkg => {
    totalVolume += pkg.volume || 0;
    totalWeight += pkg.real_weight || 0;
    totalSubtotal += pkg.subtotal || 0;
  });

  document.getElementById('totalVolume').textContent = totalVolume.toFixed(2);
  document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
  document.getElementById('totalSubtotal').textContent = totalSubtotal.toLocaleString('id-ID', {
    style: 'currency',
    currency: 'IDR'
  });
}
