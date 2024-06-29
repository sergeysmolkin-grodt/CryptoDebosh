document.addEventListener('DOMContentLoaded', function () {
    const spotBotForm = document.getElementById('spot-bot-form');
    const futuresBotForm = document.getElementById('futures-bot-form');

    spotBotForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(spotBotForm);
        const symbol = formData.get('symbol');
        const investment = formData.get('investment');

        fetch('/api/trading-bots/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                symbol: symbol,
                investment: investment
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Spot Bot executed successfully');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
    });

    futuresBotForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(futuresBotForm);
        const symbol = formData.get('symbol');
        const investment = formData.get('investment');

        fetch('/api/trading-bots/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                symbol: symbol,
                investment: investment
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Futures Bot executed successfully');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
    });
});
