'use client';

export default function Error({ error }: { error: Error }) {
  return (
    <div className="p-6 text-red-600">
      {error.message || 'Something went wrong fetching this GTIN.'}
    </div>
  );
}
