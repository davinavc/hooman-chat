import { memo } from "react";

const LastMessage = ({ lastMessage }) => {
 
    return(
        <span className="text-sm text-gray-500 dark:text-gray-400 truncate">
            {lastMessage}
        </span>
    )
}

export default memo(LastMessage);