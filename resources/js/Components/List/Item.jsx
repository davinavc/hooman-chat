import LastMessage from "./LastMessage";

const Item = ( {room} ) => {
    return(
        <div className="py-3 px-3 flex rtl:space-x-reverse hover:bg-gray-100 hover:rounded-lg dark:hover:bg-gray-800 cursor-pointer rounded-lg">
            <div className="flex-shrink-0">
                <img src={`images/${room.avatar}`} className="h-10 w-10 rounded-full bg-white" alt={room.name} />
            </div>
            <div className="flex-1 ml-2 w-0">
                <div>
                    <p className="text-sm font-medium text-gray-900 truncate dark:text-white">
                        {room.name}
                    </p>
                    <LastMessage room={room} />
                </div>
            </div>
        </div>
    )
}

export default Item;