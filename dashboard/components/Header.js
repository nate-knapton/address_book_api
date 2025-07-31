const Header = ({ 
    title = "Address Book Dashboard", 
    subtitle = "Enter an email address to get started",
    showBackButton = false,
    backButtonText = "← Back to Login",
    backButtonUrl = "../index.html"
}) => {
    return (
        <div className="header">
            {showBackButton && (
                <button 
                    className="back-button" 
                    onClick={() => window.location.href = backButtonUrl}
                >
                    {backButtonText}
                </button>
            )}
            <h1>{title}</h1>
            <p>{subtitle}</p>
        </div>
    );
};

window.Header = Header;
