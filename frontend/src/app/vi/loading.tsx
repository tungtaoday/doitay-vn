export default function Loading() {
  return (
    <div className="mx-auto max-w-3xl px-4 py-10">
      <div className="mb-6 h-7 w-48 animate-pulse rounded bg-surface-container" />
      <div className="space-y-4">
        {Array.from({ length: 4 }).map((_, i) => (
          <div
            key={i}
            className="animate-pulse rounded-2xl border border-outline-variant/20 bg-surface-container-low p-5"
          >
            <div className="mb-3 flex items-center justify-between">
              <div className="h-4 w-1/3 rounded bg-surface-container" />
              <div className="h-6 w-20 rounded-full bg-surface-container" />
            </div>
            <div className="space-y-2">
              <div className="h-3 w-full rounded bg-surface-container" />
              <div className="h-3 w-1/2 rounded bg-surface-container" />
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
