const EmailForm = () => {
    const { useState } = React;
    
    const [email, setEmail] = useState('');
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState('');
    const [messageType, setMessageType] = useState('');

    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        setMessage('');
        setMessageType('');

        if (!email.trim()) {
            setMessage('Please enter an email address');
            setMessageType('error');
            return;
        }

        if (!validateEmail(email)) {
            setMessage('Please enter a valid email address');
            setMessageType('error');
            return;
        }

        setLoading(true);

        try {
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            setMessage(`Email "${email}" submitted successfully!`);
            setMessageType('success');
            setEmail('');
        } catch (error) {
            setMessage('An error occurred while submitting the email');
            setMessageType('error');
        } finally {
            setLoading(false);
        }
    };

    const handleEmailChange = (e) => {
        setEmail(e.target.value);
    };

    const hasEmailError = messageType === 'error' && !validateEmail(email) && email;

    return (
        <div className="container">
            <Header />

            <form onSubmit={handleSubmit}>
                <EmailInput
                    email={email}
                    onChange={handleEmailChange}
                    disabled={loading}
                    hasError={hasEmailError}
                />

                <SubmitButton
                    loading={loading}
                    loadingText="Submitting..."
                >
                    Submit Email
                </SubmitButton>
            </form>

            <Message
                message={message}
                type={messageType}
                show={!!message}
            />
        </div>
    );
};

window.EmailForm = EmailForm;
