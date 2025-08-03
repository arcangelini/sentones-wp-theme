import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Placeholder, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { selectedCategory } = attributes;

	const { categories, posts, isLoadingCategories, isLoadingPosts } = useSelect(
		(select) => {
			const { getEntityRecords, isResolving } = select(coreStore);
			
			const categoriesQuery = { per_page: -1, hide_empty: true };
			const postsQuery = selectedCategory 
				? { per_page: 3, categories: selectedCategory, _embed: true }
				: { per_page: 3, _embed: true };

			return {
				categories: getEntityRecords('taxonomy', 'category', categoriesQuery),
				posts: getEntityRecords('postType', 'post', postsQuery),
				isLoadingCategories: isResolving('getEntityRecords', ['taxonomy', 'category', categoriesQuery]),
				isLoadingPosts: isResolving('getEntityRecords', ['postType', 'post', postsQuery])
			};
		},
		[selectedCategory]
	);

	const categoryOptions = [
		{ value: '', label: __('Select a category', 'categories-section') },
		...(categories || []).map((category) => ({
			value: category.id,
			label: category.name,
		})),
	];

	const onCategoryChange = (categoryId) => {
		setAttributes({ selectedCategory: parseInt(categoryId) || '' });
	};

	const getFeaturedImage = (post) => {
		if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
			return post._embedded['wp:featuredmedia'][0].source_url;
		}
		return null;
	};

	const formatDate = (dateString) => {
		const date = new Date(dateString);
		return date.toLocaleDateString();
	};

	return (
		<div {...useBlockProps()}>
			<InspectorControls>
				<PanelBody title={__('Category Settings', 'categories-section')}>
					{isLoadingCategories ? (
						<Spinner />
					) : (
						<SelectControl
							label={__('Category', 'categories-section')}
							value={selectedCategory}
							options={categoryOptions}
							onChange={onCategoryChange}
						/>
					)}
				</PanelBody>
			</InspectorControls>

			<div className="categories-section">
				<div className="category-dropdown">
					{isLoadingCategories ? (
						<Spinner />
					) : (
						<SelectControl
							label={__('Select Category', 'categories-section')}
							value={selectedCategory}
							options={categoryOptions}
							onChange={onCategoryChange}
						/>
					)}
				</div>

				<div className="posts-grid">
					{isLoadingPosts ? (
						<Placeholder>
							<Spinner />
							<span>{__('Loading posts...', 'categories-section')}</span>
						</Placeholder>
					) : posts && posts.length > 0 ? (
						<div className="posts-row">
							{posts.slice(0, 3).map((post) => (
								<div key={post.id} className="post-item">
									{getFeaturedImage(post) && (
										<div className="post-image">
											<img 
												src={getFeaturedImage(post)} 
												alt={post.title.rendered}
											/>
										</div>
									)}
									<h3 className="post-title">{post.title.rendered}</h3>
									<div className="post-date">{formatDate(post.date)}</div>
								</div>
							))}
						</div>
					) : (
						<Placeholder>
							{selectedCategory 
								? __('No posts found in this category.', 'categories-section')
								: __('Select a category to display posts.', 'categories-section')
							}
						</Placeholder>
					)}
				</div>
			</div>
		</div>
	);
}
