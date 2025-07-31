const EmailInput = ({ 
    email, 
    onChange, 
    disabled, 
    hasError, 
    placeholder = "Enter your email address",
    label = "Email Address"
}) => {
    return (
        <div className="form-group">
            <label htmlFor="email">{label}</label>
            <input
                type="email"
                id="email"
                value={email}
                onChange={onChange}
                placeholder={placeholder}
                className={hasError ? 'error' : ''}
                disabled={disabled}
            />
        </div>
    );
};

window.EmailInput = EmailInput;
