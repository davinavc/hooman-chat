import Item from "./Item";

export default function index({ title, data }) {
    if(!data || data.length === 0) return;

    return(
        <>
        <p className="text-white">{title}</p>
        {data.map((room) => (
            <Item key={room.id} room={room} />
        ))}
        </>
    )
}