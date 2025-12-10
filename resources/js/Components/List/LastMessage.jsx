import { useEffect, useState } from "react";

const LastMessage = ({ room }) => {
    if(room.type == 'contact') return;

    const [message, setMessage] = useState('');

    useEffect(() => {
        if(room.lastMessage){
            console.log(room.lastMessage.message);
            setMessage(room.lastMessage.message);
    }
    }, [room]);
    
    return(
        <span className="text-sm text-gray-500 dark:text-gray-400 truncate">
            {message}
        </span>
    )
}

export default LastMessage;