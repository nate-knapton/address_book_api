const SubmitButton = ({ 
    loading, 
    disabled, 
    onClick,
    type = "submit",
    loadingText = "Submitting...",
    children = "Submit"
}) => {
    return (
        <button 
            type={type}
            className="submit-btn"
            disabled={disabled || loading}
            onClick={onClick}
        >
            {loading ? (
                <>
                    <span className="loading"></span>
                    &nbsp; {loadingText}
                </>
            ) : (
                children
            )}
        </button>
    );
};

window.SubmitButton = SubmitButton;
