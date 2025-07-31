const Message = ({ 
    message, 
    type = 'info',
    show = true 
}) => {
    if (!show || !message) return null;

    return (
        <div className={`message ${type}`}>
            {message}
        </div>
    );
};

window.Message = Message;
