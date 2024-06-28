import React, { useState, useEffect } from 'react';

function MarketData() {
    const [marketData, setMarketData] = useState([]);

    useEffect(() => {
        fetch('/api/market-data')
            .then(response => response.json())
            .then(data => setMarketData(data));
    }, []);

    return (
        <div>
            <h2>Текущие курсы криптовалют</h2>
            <table>
                <thead>
                <tr>
                    <th>Криптовалюта</th>
                    <th>Цена</th>
                </tr>
                </thead>
                <tbody>
                {marketData.map(data => (
                    <tr key={data.symbol}>
                        <td>{data.symbol}</td>
                        <td>{data.price}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}

export default MarketData;
