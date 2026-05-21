/*// ===== Ambil Element =====
const cpuInput = document.getElementById('cpuInput');
const ramInput = document.getElementById('ramInput');
const trafficInput = document.getElementById('trafficInput');

const cpuValue = document.getElementById('cpuValue');
const ramValue = document.getElementById('ramValue');

// ===== Chart.js =====
const ctx = document.getElementById('trafficChart');

const trafficChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4'],
        datasets: [{
            label: 'Network Traffic',
            data: [1200, 1300, 1400, 1500],
            borderWidth: 2,
            fill: true
        }]
    }
});

// ===== Update Function =====
function updateDashboard() {
    cpuValue.innerText = cpuInput.value + '%';
    ramValue.innerText = ramInput.value + '%';

    // update chart data
    trafficChart.data.datasets[0].data.shift();
    trafficChart.data.datasets[0].data.push(Number(trafficInput.value));
    trafficChart.update();
}

// ===== Event Listener =====
cpuInput.addEventListener('input', updateDashboard);
ramInput.addEventListener('input', updateDashboard);
trafficInput.addEventListener('input', updateDashboard);
*/