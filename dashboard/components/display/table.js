// Table Component for displaying data
const Table = ({ 
    items = []
}) => {
    
    return () => {
        if (!items.length) {
            return <div className="no-data">No data available</div>;
        }

        return (
            <table className="data-table">
                <thead>
                    <tr>
                        {Object.keys(items[0]).map((key) => (
                            <th key={key}>{key}</th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {items.map((item, index) => (
                        <tr key={index}>
                            {Object.values(item).map((value, idx) => (
                                <td key={idx}>{value}</td>
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        );
    }

};

// Make it available globally
window.Message = Message;
