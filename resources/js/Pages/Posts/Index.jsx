

export default function Index({ posts }) {
    return (
        <div>
            <h1>Posts</h1>
            <ul>
                {posts.map((post) => (
                    <li key={post.id}>
                        <h2>{post.title}</h2>
                        <p>Especie: {post.species?.name ?? 'Sin especie'}</p>
                        <p>Autor: {post.user?.name ?? 'Usuario eliminado    '}</p>
                    </li>
                ))}
            </ul>
        </div>
    );
}